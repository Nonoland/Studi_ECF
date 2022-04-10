<?php

namespace App\Controller\Admin;

use App\Entity\Hotel;
use App\Entity\User;
use Ausi\SlugGenerator\SlugOptions;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\QueryBuilder;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use Ausi\SlugGenerator\SlugGenerator;

class HotelCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Hotel::class;
    }


    public function configureFields(string $pageName): iterable
    {
        return [
            TextField::new('name'),
            TextEditorField::new('description'),
            TextField::new('address'),
            TextField::new('complement')->setRequired(false),
            TextField::new('zipcode'),
            TextField::new('city'),
            AssociationField::new('users')->setQueryBuilder(
                fn (QueryBuilder $queryBuilder) => $queryBuilder->getEntityManager()->getRepository(User::class)->getAllUsers()
            )
        ];
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

}
