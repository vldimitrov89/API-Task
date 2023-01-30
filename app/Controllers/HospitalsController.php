<?php

namespace Api\app\Controllers;

use Api\app\Models\Hospital;
use Rakit\Validation\Validator;

class HospitalsController
{
    public function index()
    {
        $hospital = Hospital::query();

        if (input()->exists('userCountOrder')) {
            $hospital->withCount('users')->orderBy('users_count', 'desc');
        }

        response()->json($hospital->get());
    }

    public function show($id)
    {
        $hospital = Hospital::where('id', '=', $id)->first();

        !is_null($hospital) ? response()->json($hospital) : response()->json([]);
    }

    public function store()
    {
        $data = input()->all([
            'name',
            'address',
            'phone'
        ]);

        //validation
        $validator = new Validator();

        $validationData = $validator->validate($data, [
            'name' => 'required|min:2|max:255|regex:/[A-Za-z0-9\-\\,.]+/',
            'address' => 'required|min:10|max:255|regex:/[A-Za-z0-9\-\\,.]+/',
            'phone' => 'required|numeric',
        ]);

        if ($validationData->fails()) {
            response()->json(['validation_errors' => $validationData->errors()->all()]);
        }

        //sanitize data
        foreach ($data as $key => $entry) {
            $data[$key] = filter_var($entry, FILTER_SANITIZE_STRING);
        }

        $hospital = new Hospital();

        $hospital->create($data);

        response()->json(['Hospital is created']);
    }

    public function update($id)
    {
        $hospital = Hospital::where('id', '=', (int)$id)->first();

        if (!is_null($hospital)) {
            $data = input()->all([
                'name',
                'address',
                'phone'
            ]);

            //validation
            $validator = new Validator();

            $validationData = $validator->validate($data, [
                'name' => 'required|min:2|max:255',
                'address' => 'required|min:10|max:255',
                'phone' => 'required|numeric',
            ]);

            if ($validationData->fails()) {
                // handling errors
                response()->json(['validation_errors' => $validationData->errors()->all()]);
            }

            //sanitize data
            foreach ($data as $key => $entry) {
                $data[$key] = filter_var($entry, FILTER_SANITIZE_STRING);
            }

            $hospital->update($data);

            response()->json(['Hospital is updated']);
        }

        response()->json(['Hospital is not found']);
    }

    public function destroy($id)
    {
        $hospital = Hospital::where('id', '=', $id)->first();

        if (!is_null($hospital)) {
            if (count($hospital->users) > 0) {
                response()->json(['You can\'t delete this hospital, it is a workplace for (' . count($hospital->users) . ') doctors.']);
            }
            if ($hospital->delete()) {
                response()->json(['Hospital is deleted']);
            }
            response()->json(['Hospital is not deleted']);
        }
        response()->json(['Hospital is not found']);
    }
}
