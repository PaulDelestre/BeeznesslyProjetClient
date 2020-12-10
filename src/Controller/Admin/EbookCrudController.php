<?php

namespace App\Controller\Admin;

use App\Entity\Ebook;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class EbookCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Ebook::class;
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
