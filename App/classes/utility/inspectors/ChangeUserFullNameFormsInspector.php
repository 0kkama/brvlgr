<?php

    namespace App\classes\utility\inspectors;

    class ChangeUserFullNameFormsInspector extends FormsInspector
    {
        protected static array $errorsAbsence =
            [
                'firstName' => 'Отсутствует имя',
                'middleName' => 'Отсутствует отчество',
                'lastName' => 'Отсутствует фамилия',
            ];

        protected function prepareData(): void
        {
            // TODO: Implement prepareData() method.
        }


    }
