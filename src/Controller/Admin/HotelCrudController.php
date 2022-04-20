<?php

namespace App\Controller\Admin;

use App\Entity\Hotel;
use App\Entity\User;
use Ausi\SlugGenerator\SlugOptions;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\QueryBuilder;
use EasyCorp\Bundle\EasyAdminBundle\Collection\FieldCollection;
use EasyCorp\Bundle\EasyAdminBundle\Collection\FilterCollection;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Dto\EntityDto;
use EasyCorp\Bundle\EasyAdminBundle\Dto\SearchDto;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use Ausi\SlugGenerator\SlugGenerator;
use EasyCorp\Bundle\EasyAdminBundle\Orm\EntityRepository;

class HotelCrudController extends AbstractCrudController
{

    public function __construct(EntityRepository $entityRepository)
    {
        $this->entityRepository = $entityRepository;
    }

    public static function getEntityFqcn(): string
    {
        return Hotel::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            TextField::new('name', 'Nom'),
            TextEditorField::new('description', 'Description'),
            TextField::new('address', 'Adresse'),
            TextField::new('complement', 'Complément')->setRequired(false),
            TextField::new('zipcode', 'Code postal'),
            TextField::new('city', 'Ville'),
            AssociationField::new('users', 'Gérants')->setQueryBuilder(
                fn (QueryBuilder $queryBuilder) => $queryBuilder->getEntityManager()->getRepository(User::class)->getAllUsers()
            )->setFormTypeOptions([
                'by_reference' => false
            ])
        ];
    }

    public function createIndexQueryBuilder(SearchDto $searchDto, EntityDto $entityDto, FieldCollection $fields, FilterCollection $filters): QueryBuilder
    {
        $response = parent::createIndexQueryBuilder($searchDto, $entityDto, $fields, $filters);
        if (in_array('ROLE_ADMIN', $this->getUser()->getRoles()))
            return $response;

        $response
            ->leftJoin('entity.users', 'users')
            ->andWhere('users.id = :user')
            ->setParameter('user', $this->getUser());

        return $response;
    }

    public function configureActions(Actions $actions): Actions
    {
        return $actions->add(Crud::PAGE_INDEX, 'detail');
    }

    public function persistEntity(EntityManagerInterface $entityManager, $entityInstance): void
    {
        $slugGenerator = new SlugGenerator((new SlugOptions)
            ->setLocale('fr')
            ->setDelimiter('_')
            ->setValidChars('a-zA-Z0-9')
        );
        $entityInstance->setSlug($slugGenerator->generate($entityInstance->getName()));
        parent::persistEntity($entityManager, $entityInstance);
    }

    /** @var $entityInstance Hotel */
    public function updateEntity(EntityManagerInterface $entityManager, $entityInstance): void
    {
        parent::updateEntity($entityManager, $entityInstance);

        foreach ($entityInstance->getUsers() as $user) {
            $user->setRoles((array)$user->getRoles()[] = 'ROLE_MANAGER');
            $entityManager->persist($user);
            $entityManager->flush();
        }
    }

}
