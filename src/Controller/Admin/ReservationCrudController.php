<?php

namespace App\Controller\Admin;

use App\Entity\Reservation;
use App\Entity\Suite;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\QueryBuilder;
use EasyCorp\Bundle\EasyAdminBundle\Collection\FieldCollection;
use EasyCorp\Bundle\EasyAdminBundle\Collection\FilterCollection;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Dto\EntityDto;
use EasyCorp\Bundle\EasyAdminBundle\Dto\SearchDto;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateField;

class ReservationCrudController extends AbstractCrudController
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public static function getEntityFqcn(): string
    {
        return Reservation::class;
    }

    public function configureFields(string $pageName): iterable
    {
        $suites = $this->entityManager->getRepository(Suite::class)->findAll();

        return [
            AssociationField::new('suite', 'Suite sélectionnée')->setQueryBuilder(
                function (QueryBuilder $queryBuilder) {
                    //$queryBuilder->getEntityManager()->getRepository(Suite::class)->findAll();
                    if (in_array('ROLE_ADMIN', $this->getUser()->getRoles()))
                        return $queryBuilder;

                    return $queryBuilder
                        ->leftJoin('entity.hotel', 'hotel')
                        ->leftJoin('hotel.users', 'users')
                        ->andWhere('users.id = :user')
                        ->setParameter('user', $this->getUser());
                }
            ),
            AssociationField::new('user', 'Utilisateur')->setQueryBuilder(
                fn (QueryBuilder $queryBuilder) => $queryBuilder->getEntityManager()->getRepository(User::class)->getAllUsers()
            ),
            DateField::new('date_start', 'Date de début'),
            DateField::new('date_end', 'Date de fin'),
        ];
    }

    public function createIndexQueryBuilder(SearchDto $searchDto, EntityDto $entityDto, FieldCollection $fields, FilterCollection $filters): QueryBuilder
    {
        $response = parent::createIndexQueryBuilder($searchDto, $entityDto, $fields, $filters);
        if (in_array('ROLE_ADMIN', $this->getUser()->getRoles()))
            return $response;

        $response
            ->leftJoin('entity.suite', 'suite')
            ->leftJoin('suite.hotel', 'hotel')
            ->leftJoin('hotel.users', 'users')
            ->andWhere('users.id = :user')
            ->setParameter('user', $this->getUser());

        return $response;
    }

}
