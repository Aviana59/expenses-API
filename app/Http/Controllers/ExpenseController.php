<?php

namespace App\Http\Controllers;

use App\Http\Requests\ExpenseRequest;
use App\Models\ExpenseModel;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;


class ExpenseController extends Controller
{

    public function show(int $id)
    {
        $expense = ExpenseModel::where('id', '=', $id)->get();
        return response()->json(['message' => 'success', 'data' => $expense])->setStatusCode(200);
    }

    public function index(Request $request)
    {

        if ($request->start && $request->end) { // if there are input of filter

            // get expense where date >= request_input_start and date <= request_input_end
            $expenses = ExpenseModel::where('date', '>=', $request->start)->where('date', '<=', $request->end)->get();
        } else {
            // get all data
            $expenses = ExpenseModel::all();
        }

        return response()->json(['message' => 'success', 'data' => $expenses])->setStatusCode(200);
    }

    public function create(ExpenseRequest $request): JsonResponse
    {
        $expense = new ExpenseModel(); // initialize new data expense

        // set the value
        $expense->title = $request->title;
        $expense->description = $request->description;
        $expense->total = $request->total;
        $expense->date = $request->date;
        $expense->user_id = auth()->user()->id; // id logged in user

        // if error when saving data
        if (!$expense->save()) {
            return response()->json(['message' => 'something went wrong'])->setStatusCode(400);
        }

        return response()->json(['message' => 'success',  'data' => $expense]);
    }

    public function update(int $id, ExpenseRequest $request): JsonResponse
    {
        // find data by id
        $expense = ExpenseModel::find($id);

        // if data can't find data
        if (!$expense) {
            response()->json(['message' => 'success',  'data' => $expense])->setStatusCode('404');
        }

        $data = $request->validated(); //validate request

        $expense->fill($data); // set the value

        $expense->save(); // save data

        return response()->json(['message' => 'success',  'data' => $expense]);
    }

    public function delete(int $id): JsonResponse
    {
        // find data by id
        $expense = ExpenseModel::find($id);

        // if data can't find data
        if (!$expense) {
            response()->json(['message' => 'success',  'data' => $expense])->setStatusCode('404');
        }

        $expense->delete(); // delete data

        return response()->json(['message' => 'success']);
    }
}
