<?php

namespace App\Controller\Admin;

use App\Entity\CacAddress;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class CacAddressCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return CacAddress::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            TextField::new('name', 'Nom'),
            TextField::new('address', 'Adresse'),
            TextField::new('postal', 'Code postal'),
            TextField::new('city', 'Ville/Commune'),
        ];
    }
}
