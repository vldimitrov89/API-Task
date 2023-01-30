<?php

namespace Api\app\Controllers;

use Rakit\Validation\Validator;
use Api\app\Models\User as User;
use Api\app\Models\Hospital as Hospital;
use Api\app\Rules\UniqueEmailRule as UniqueEmailRule;

class UsersController
{
    /*
        Expose functionality to create, read, update and delete (CRUD) users and hospitals.
        Expose functionality to list users and the ability to search them by workplace and title.
    */

    public function index()
    {
        $user = User::query();

        if (input()->exists('first_name')) {
            $first_name = input()->value('first_name');
            //sanitize input
            $first_name = filter_var($first_name, FILTER_SANITIZE_STRING);

            $user->where('first_name', 'like', '%' . $first_name);
        }

        if (input()->exists('last_name')) {
            $last_name = input()->value('last_name');
            //sanitize input
            $last_name = filter_var($last_name, FILTER_SANITIZE_STRING);

            $user->where('last_name', 'like', '%' . $last_name);
        }

        if (input()->exists('hospital')) {
            $hospital = input()->value('hospital');
            //sanitize input
            $hospital = filter_var($hospital, FILTER_SANITIZE_STRING);

            $hospital = Hospital::where('name', '=', $hospital)->first();

            !is_null($hospital) ? response()->json($hospital->users) : response()->json([]);
        }



        response()->json($user->get());
    }

    public function show($id)
    {
        $user = User::where('id', '=', $id)->first();

        is_null($user) ? response()->json([]) : response()->json($user);
    }

    public function store()
    {
        $data = input()->all([
            'email',
            'first_name',
            'last_name',
            'type',
            'workplace_id'
        ]);

        //validation
        $validator = new Validator();

        $validator->addValidator('unique', new UniqueEmailRule(new User()));

        $validationData = $validator->validate($data, [
            'email' => 'required|email|unique:email',
            'first_name' => 'required|min:2|max:255|regex:/[A-Za-z0-9\-\\,.]+/',
            'last_name' => 'required|min:2|max:255|regex:/[A-Za-z0-9\-\\,.]+/',
            'type' => 'required|numeric',
            'workplace_id' => 'numeric'
        ]);

        if ($validationData->fails()) {
            // handling errors
            response()->json(['validation_errors' => $validationData->errors()->all()]);
        }

        //check if user is a doctor
        if ((int)input()->value('type') === 2 && !input()->exists('workplace_id')) {
            response()->json(['To create Doctor, please provide Workplace Id']);
        }

        //check if workplace_id exists
        if ((int)input()->value('type') === 2 && input()->exists('workplace_id')) {
            $workplace = input()->value('workplace_id');
            //sanitize input
            $workplace = filter_var($workplace, FILTER_SANITIZE_STRING);

            $hospital = Hospital::where('id', '=', $workplace)->first();

            if (is_null($hospital)) {
                response()->json(['Chosen Workplace does not exist']);
            }
        }

        //check if user is patient and workplace is entered
        if ((int)input()->value('type') === 1 && input()->exists('workplace_id')) {
            response()->json(['Patient can not have Workplace Id']);
        }

        //sanitize data
        foreach ($data as $key => $entry) {
            if ($entry != null) {
                $data[$key] = filter_var($entry, FILTER_SANITIZE_STRING);
            }
        }

        $user = new User();

        $user->create($data);

        response()->json(['User is created']);
    }

    public function update($id)
    {
        $user = User::where('id', '=', (int)$id)->first();

        if (!is_null($user)) {
            $data = input()->all([
                'email',
                'first_name',
                'last_name',
                'type',
                'workplace_id'
            ]);

            //validation
            $validator = new Validator();

            $validator->addValidator('unique', new UniqueEmailRule(new User()));

            $validationData = $validator->validate($data, [
                'email' => 'email|unique:email',
                'first_name' => 'required|min:2|max:255|regex:/[A-Za-z0-9\-\\,.]+/',
                'last_name' => 'required|min:2|max:255|regex:/[A-Za-z0-9\-\\,.]+/',
                'type' => 'required|numeric',
                'workplace_id' => 'numeric'
            ]);

            if ($validationData->fails()) {
                // handling errors
                response()->json(['validation_errors' => $validationData->errors()->all()]);
            }

            //check if user is a doctor
            if ((int)input()->value('type') === 2 && !input()->exists('workplace_id')) {
                response()->json(['To create Doctor, please provide Workplace Id']);
            }

            //check if workplace_id exists
            if ((int)input()->value('type') === 2 && input()->exists('workplace_id')) {
                $workplace = input()->value('workplace_id');
                //sanitize input
                $workplace = filter_var($workplace, FILTER_SANITIZE_STRING);

                $hospital = Hospital::where('id', '=', $workplace)->first();

                if (is_null($hospital)) {
                    response()->json(['Chosen Workplace does not exist']);
                }
            }

            //check if user is patient and workplace is entered
            if ((int)input()->value('type') === 1 && input()->exists('workplace_id')) {
                response()->json(['Patient can not have Workplace Id']);
            }

            //sanitize data
            foreach ($data as $key => $entry) {
                if ($entry != null) {
                    $data[$key] = filter_var($entry, FILTER_SANITIZE_STRING);
                }
            }

            $user->update($data);

            response()->json(['User is updated']);
        }

        response()->json(['User not found']);
    }

    public function destroy($id)
    {
        $user = User::where('id', '=', (int)$id)->first();

        if (!is_null($user)) {
            if ($user->delete()) {
                response()->json(['User is deleted']);
            }
            response()->json(['User is not deleted']);
        }
        response()->json(['User not found']);
    }
}
