<?php

namespace App\Controller\Admin;

use App\Entity\Reservation;
use App\Entity\Suite;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\QueryBuilder;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
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
                fn (QueryBuilder $queryBuilder) => $queryBuilder->getEntityManager()->getRepository(Suite::class)->findAll()
            ),
            AssociationField::new('user', 'Utilisateur')->setQueryBuilder(
                fn (QueryBuilder $queryBuilder) => $queryBuilder->getEntityManager()->getRepository(User::class)->getAllUsers()
            ),
            DateField::new('date_start', 'Date de début'),
            DateField::new('date_end', 'Date de fin'),
        ];
    }

}
