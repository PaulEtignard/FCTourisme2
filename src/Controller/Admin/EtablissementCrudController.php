<?php

namespace App\Controller\Admin;

use App\Entity\Etablissement;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class EtablissementCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Etablissement::class;
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
