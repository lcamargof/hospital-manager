<?php namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Auth;
use App\Patient;
use App\Bed;
use App\Doctor;

class PatientController extends Controller {

   /**
   * Display a listing of the resource.
   *
   * @return Response
   */
   public function index() {
      if(Auth::user()->role == 'receptionist') {
         $patients = Patient::with('bed')->get();
         $beds = Bed::where('patient_id', '')->get();

         return view('reception.receptionlayout')
            ->with('location', 'patients')
            ->nest('content', 'reception.patientsIndex', array(
               'patients' => $patients,
               'beds' => $beds
            ));
      } else {
         $patients = Auth::user()->doctor->patientsRecords;

         return view('doctor.doctorlayout')
            ->with('location', 'patients')
            ->nest('content', 'doctor.patientsIndex', array(
               'patients' => $patients
            ));
      }
   }

   /**
   * Store a newly created resource in storage.
   *
   * @return Response
   */
   public function store(Request $request) {
      // Validate input
      $this->validate($request, [
         'name'       => 'required',
         'id_number'  => 'required|unique:patients',
         'gender'     => 'required',
         'birth_date' => 'required|date',
         'blood_type' => 'required',
         'phone'      => 'required',
         'address'    => 'required',
      ]);

      // Create the new patient
      $patient = Patient::create(array_except($request->input(), ['_token']));

      // Return the json response
      return response()->json([
         'result' => 'success', 
         'msg' => '<strong> Success!!!</strong> Patient created successfully.', 
         'row' => Patient::where('id', $patient->id)->with('bed')->get()
      ]);
   }

   /**
   * Display the specified resource.
   *
   * @param  int  $id
   * @return Response
   */
   public function show(Request $request, $id) {

   }

   /**
   * Update the specified resource in storage.
   *
   * @param  int  $id
   * @return Response
   */
   public function update(Request $request, $id) {
      if(Auth::user()->role == 'receptionist') {
         // Validate input
         $this->validate($request, [
         'name'       => 'required',
         'id_number'  => 'required|unique:patients,id_number,'.$id,
         'gender'     => 'required',
         'birth_date' => 'required|date',
         'blood_type' => 'required',
         'phone'      => 'required',
         'address'    => 'required',
         ]);

         // Create the new patient
         $patient = Patient::find($id);
         $patient->name         = $request->input('name');
         $patient->id_number    = $request->input('id_number');
         $patient->gender       = $request->input('gender');
         $patient->birth_date   = $request->input('birth_date');
         $patient->blood_type   = $request->input('blood_type');
         $patient->phone        = $request->input('phone');
         $patient->address      = $request->input('address');
         $patient->allergies    = $request->input('allergies');
         $patient->observations = $request->input('observations');
         $patient->save();

         // Return the json response
         return response()->json([
            'result' => 'success', 
            'msg' => '<strong> Success!!!</strong> Patient information updated successfully.', 
            'row' => Patient::where('id', $patient->id)->with('bed')->get()->first()
         ]);        
      } else { // IF DOCTOR
         // Edit the patient
         $patient = Patient::find($id);
         $patient->allergies = $request->input('allergies');
         $patient->observations = $request->input('observations');
         $patient->save();

         // Return the json response
         return response()->json([
            'result' => 'success', 
            'msg' => '<strong> Success!!!</strong> Patient information updated successfully.', 
            'row' => Patient::where('id', $patient->id)->with('records')->get()->first()
         ]);
      }
   }

   /**
   * Remove the specified resource from storage.
   *
   * @param  int  $id
   * @return Response
   */
   public function destroy($id) {
      $patient = Patient::where('id', $id)->with('records')->get()->first();

      if(!count($patient->records)) {
         if(Patient::destroy($id)) {
            return response()->json(['result' => 'success', 'msg' => 'Patient removed successfully.']);
         } else {
            return response()->json(['result' => 'error', 'msg' => 'Oops, try again.']);
         }
      } else {
            return response()->json(['result' => 'error', 'msg' => 'The patient have records.']);         
      }
   }
}

?>