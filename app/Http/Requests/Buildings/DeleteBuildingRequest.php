<?php

namespace App\Http\Requests\Buildings;

use App\Models\Building;
use Store;
use Exception;
use Entrust;

use App\Http\Requests\Request;
use App\Validators\Validator;

class DeleteBuildingRequest extends Request
{
    /**
     * Overwrite laravel's method, define custom validator
     */
    public function validate()
    {
        $this->validator = Validator::make();

        $this->rules();
        $this->runValidator();
    }

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        // TODO: here should be checking for ownership too

        if (!Entrust::hasRole('administrator')) return false;
        
        $id = $this->route('building');

        try {
            $item = Building::findOrFail($id);
            Store::set('building', $item);

            return true;
        } catch (Exception $e) {
            return false;
        }

        return false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return $this->rules;
    }
}
