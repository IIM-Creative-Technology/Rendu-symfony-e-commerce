<?php

namespace App\Controller\Admin;

use App\Entity\Produits;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class Produits2CrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Produits::class;
    }

    /*
    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id'),
            TextField::new('title'),
            TextEditorField::new('description'),
        ];
    }
    */
}
