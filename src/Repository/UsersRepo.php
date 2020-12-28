<?php
namespace App\Repository;

class UsersRepo extends AbstractRepository
{
    /**
     * @inheritDoc
     * @return void
     */
    protected function validations(): void
    {
        $this->validator
            ->add('email', [
                'required',
                'isEmail',
            ])
            ->add('password', [
//                'maxLength' => [
//                    'maxLength' => 8,
//                    'message' => 'mai mult',
//                ],
                'minLength' => [
                    'minLength' => 5,
                    'message' => 'mai putin',
                ],
            ]);
//            ->add('age', [
//                'checkMoreThanWhateverFunction' => [
//                    'method' => 'checkMoreThanWhateverFunction',
//                    'message' => 'check more no mers'
//                ],
//            ]);
    }

    /**
     * @param $input
     * @param string $field
     * @param string $rule
     * @param string $message
     * @return void
     */
    public function checkMoreThanWhateverFunction($input, string $field, string $rule, string $message): void
    {
        if ($input > 10) {
            $this->validator->setErrors($field, $rule, $message);
        }
    }
}
