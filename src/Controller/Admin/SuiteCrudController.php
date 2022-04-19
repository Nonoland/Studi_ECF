<?php

namespace App\Controller\Admin;

use App\Controller\Admin\Field\VichImageField;
use App\Entity\Suite;
use App\Entity\User;
use App\Form\AttachmentType;
use Ausi\SlugGenerator\SlugGenerator;
use Ausi\SlugGenerator\SlugOptions;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\QueryBuilder;
use EasyCorp\Bundle\EasyAdminBundle\Collection\FieldCollection;
use EasyCorp\Bundle\EasyAdminBundle\Collection\FilterCollection;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Dto\EntityDto;
use EasyCorp\Bundle\EasyAdminBundle\Dto\SearchDto;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\CollectionField;
use EasyCorp\Bundle\EasyAdminBundle\Field\Field;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\NumberField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use Vich\UploaderBundle\Form\Type\VichImageType;
use Vich\UploaderBundle\Mapping\Annotation\UploadableField;

class SuiteCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Suite::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            TextField::new('name', 'Nom'),
            TextEditorField::new('description', 'Description'),
            NumberField::new('price', 'Prix'),
            CollectionField::new('attachments', 'Gallerie d\'images')
                ->setEntryType(AttachmentType::class)
                ->onlyOnForms(),
            VichImageField::new('attachments', 'Gallerie d\'images')
                ->setTemplatePath('admin/field/images.html.twig')
                ->onlyOnDetail(),
            TextField::new('booking_link', 'Lien vers Booking'),
            AssociationField::new('hotel', 'Hotel')->setQueryBuilder(
                function (QueryBuilder $queryBuilder) {
                    if (in_array('ROLE_ADMIN', $this->getUser()->getRoles()))
                        return $queryBuilder;

                    return $queryBuilder
                        ->leftJoin('entity.users', 'users')
                        ->andWhere('users.id = :user')
                        ->setParameter('user', $this->getUser());
                }
            ),
        ];
    }

    public function configureActions(Actions $actions): Actions
    {
        return $actions
            ->add(Crud::PAGE_INDEX, Action::DETAIL);
    }

    public function createIndexQueryBuilder(SearchDto $searchDto, EntityDto $entityDto, FieldCollection $fields, FilterCollection $filters): QueryBuilder
    {
        $response = parent::createIndexQueryBuilder($searchDto, $entityDto, $fields, $filters);

        if (in_array('ROLE_ADMIN', $this->getUser()->getRoles()))
            return $response;

        $response
            ->leftJoin('entity.hotel', 'hotel')
            ->leftJoin('hotel.users', 'users')
            ->andWhere('users.id = :user')
            ->setParameter('user', $this->getUser());

        return $response;
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
