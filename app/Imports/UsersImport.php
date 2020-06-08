<?php
   
namespace App\Imports;
   
use App\User;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
/*
 * Validation
*/
use Illuminate\Validation\Rule;
use Maatwebsite\Excel\Concerns\WithValidation;
/*
 * SkipsOnFailure
*/
use Maatwebsite\Excel\Validators\Failure;
use Maatwebsite\Excel\Concerns\SkipsOnFailure;
    
class UsersImport implements ToModel, WithHeadingRow, WithValidation, SkipsOnFailure
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        return new User([
            'first_name' => $row['first_name'],
            'last_name' => $row['last_name'], 
            'email' => $row['email'],
            'phone_number' => $row['phone_number']
        ]);
    }

     public function rules(): array
    {
        $rules = [
            'email' => 'email|required|unique:users',
        ];
        return $rules;
    }


    /**
     * @param Failure[] $failures
     */
    public function onFailure(Failure ...$failures)
    {
        // Handle the failures how you'd like.
        foreach ($failures as $failure) {
             echo 'Row: ' . $failure->row() . ' '; // row that went wrong
             echo 'Column: ' . $failure->attribute()  . ' '; // either heading key (if using heading row concern) or column index
             // $failure->errors(); // Actual error messages from Laravel validator
             echo 'Errors: ' . implode($failure->errors()) . ' '; // Actual error messages from Laravel validator
             // $failure->values(); // The values of the row that has failed.
             // echo implode($failure->values());
             echo 'Value: ' . $failure->values()[$failure->attribute()] . '<br>'; // The values of the row that has failed.
             // echo array_search($failure->attribute(), $failure->values()) . '<br>'; // The values of the row that has failed.
        }
    }


    public function customValidationMessages()
    {
        return [
            /*
             * Note: The order of the definition matters, First match is selected
             *
             *  If the email is duplicated, the message will be: Campo Unico Duplicado
             *  'unique' => 'Campo Unico Duplicado Custom message for :attribute.',
             *  'email.unique' => 'Email Duplicado Custom message for :attribute.',
             *
             *  If the email is duplicated, the message will be: Email Duplicado, and for the other validations will be: Campo Unico Duplicado
             *  'email.unique' => 'Email Duplicado Custom message for :attribute.',
             *  'unique' => 'Campo Unico Duplicado Custom message for :attribute.',
             * 
             *
            */
            '0' => 'Custom message for :attribute.',
            '1' => 'Custom message for :attribute.',
            '2' => 'Custom message for :attribute.',
            'email.email' => 'Formation de compte email incorrecte  Custom message for :attribute.',
            'email.unique' => 'Email Duplicado Custom message for :attribute.',
            'email' => 'Conformacion de correo electronico Incorrecta Custom message for :attribute.'
        ];
    }

}
