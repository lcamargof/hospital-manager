<?php namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Auth;
use App\Record;
use App\Transaction;

class TransactionController extends Controller {

  /**
   * Display a listing of the resource.
   *
   * @return Response
   */
  public function index()
  {
    return response('Unauthorized.', 401);
  }

  /**
   * Store a newly created resource in storage.
   *
   * @return Response
   */
  public function store(Request $request) {
    // Validate information
    $this->validate($request, [
      'amount' => 'required|integer',
      'method' => 'required',
      'record_id' => 'exists:records,id'
    ]);

    // Create the payment row
    Transaction::create(array_except($request->input(), ['_token']));

    // Response
    return response()->json([
      'result' => 'success', 
      'msg' => 'Record payed successfully.',
      'row' => Record::where('id', $request->input('record_id'))
                  ->with('patient')
                  ->with('doctor')
                  ->with('transaction')->get()->first()
    ]);
  }

  /**
   * Update the specified resource in storage.
   *
   * @param  int  $id
   * @return Response
   */
  public function update($id)
  {
    return response('Unauthorized.', 401);
  }

  /**
   * Remove the specified resource from storage.
   *
   * @param  int  $id
   * @return Response
   */
  public function destroy($id)
  {
    return response('Unauthorized.', 401);
  }
  
}

?>