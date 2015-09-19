<?php namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Auth;
use App\Record;
use App\Transaction;
use App\Doctor;
use App\Patient;

class RecordController extends Controller {

   /**
   * Display a listing of the resource.
   *
   * @return Response
   */
   public function index() {
      switch (Auth::user()->role) {
         case 'receptionist':
            $records = Record::with('patient')
                           ->with('doctor')
                           ->with('transaction')
                           ->get();
            $doctors = Doctor::where('active', 1)->get();
            $patients = Patient::all();

            return view('reception.receptionlayout')
               ->with('location', 'records')
               ->nest('content', 'reception.recordsIndex', array(
                  'records' => $records,
                  'doctors' => $doctors,
                  'patients' => $patients
               ));
         
         case 'doctor':
            $records = Auth::user()->doctor->records;
            $patients = Auth::user()->doctor->patients;
            $doctor = Auth::user()->doctor;

            return view('doctor.doctorlayout')
               ->with('location', 'records')
               ->nest('content', 'doctor.recordsIndex', array(
                  'records' => $records,
                  'patients' => $patients,
                  'doctor' => $doctor
               ));

         default:
            return response('Unauthorized.', 401);
      }
   }

   /**
   * Store a newly created resource in storage.
   *
   * @return Response
   */
   public function store(Request $request) {
      // Validate the input
      $this->validate($request, [
         'description' => 'required',
         'type' => 'required',
         'type_other' => 'required_if:type,other',
         'date_to' => 'required|date|unique:records,date_to,NULL,id,doctor_id,'.$request->input('doctor_id'),
         'doctor_id' => 'required|exists:doctors,id',
         'patient_id' => 'required|exists:patients,id'
      ]);

      // Create the new record
      $record = Record::create(array_except($request->input(), ['_token']));

      // Return the json response
      return response()->json([
         'result' => 'success', 
         'msg' => '<strong> Success!!!</strong> Record created successfully.', 
         'row' => Record::where('id', $record->id)
                  ->with('patient')
                  ->with('doctor')
                  ->with('transaction')->get()
      ]);
   }

   /**
   * Update the specified resource in storage.
   *
   * @param  int  $id
   * @return Response
   */
   public function update(Request $request, $id) {
      // Validate the input
      $this->validate($request, [
         'description' => 'required',
         'type' => 'required',
         'type_other' => 'required_if:type,other',
         'date_to' => 'required|date|unique:records,date_to,'.$id.',id,doctor_id,'.$request->input('doctor_id'),
         'doctor_id' => 'required|exists:doctors,id',
         'patient_id' => 'required|exists:patients,id'
      ]);

      // Create the new record
      $record = Record::find($id);
      $record->description = $request->input('description');
      $record->type = $request->input('type');
      $record->type_other = $request->input('type_other');
      $record->date_to = $request->input('date_to');
      $record->doctor_id = $request->input('doctor_id');
      $record->patient_id = $request->input('patient_id');
      if(Auth::user()->role == 'doctor')
         $record->results = $request->input('results');
      $record->save();

      // Return the json response
      return response()->json([
         'result' => 'success', 
         'msg' => '<strong> Success!!!</strong> Record updated successfully.', 
         'row' => Record::where('id', $record->id)
                  ->with('patient')
                  ->with('doctor')
                  ->with('transaction')->get()->first()
      ]);
   }

   /**
   * Remove the specified resource from storage.
   *
   * @param  int  $id
   * @return Response
   */
   public function destroy($id) {
      $record = Record::where('id', $id)->with('transaction')->get()->first();

      if(!count($record->transaction)) {
         if(Record::destroy($id)) {
            return response()->json(['result' => 'success', 'msg' => 'Record removed successfully.']);
         } else {
            return response()->json(['result' => 'error', 'msg' => 'Oops, try again.']);
         }         
      } else {
         return response()->json(['result' => 'error', 'msg' => 'The patient have records.']);
      }
   }
}

?>