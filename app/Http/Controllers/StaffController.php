<?php namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Auth;
use App\Doctor;
use App\Nurse;
use App\User;
use App\Bed;

class StaffController extends Controller {

   /**
   * Display a listing of the resource.
   *
   * @return Response
   */
   public function index() {
      // Retrieving the data
      $doctors = Doctor::where('active', 1)->get();
      $nurses  = Nurse::with('beds')->get();
      $beds = Bed::all();

      // Defining the layout by user role
      if(Auth::user()->role == 'master') {
         return view('admin.adminlayout')
            ->with('location', 'staff')
            ->nest('content', 'admin.staffIndex', array('doctors' => $doctors, 'nurses' => $nurses,
               'beds' => $beds));        
      } else {
         return view('reception.receptionlayout')
            ->with('location', 'staff')
            ->nest('content', 'reception.staffIndex', array('doctors' => $doctors, 'nurses' => $nurses));            
      }
   }

   /**
   * Store a newly created resource in storage.
   *
   * @return Response
   */
   public function store(Request $request) {
      // Only admit ajax request and master user
      if ($request->ajax() && Auth::user()->role === 'master') {
         switch ($request->input('type')) {
            case 'doctor':
               // Retrieving the models object
               $user = new User();

               // Validating the fields
               $this->validate($request, [
               'name'           => 'required',
               'id_number'      => 'required|unique:doctors',
               'birth_date'     => 'required|date',
               'specialization' => 'required',
               'time_in'        => 'required',
               'time_out'       => 'required|different:time_in'
               ]);

               // Array of doctor data
               $newDoctor = array(
               'name'           => $request->input('name'),
               'id_number'      => $request->input('id_number'),
               'birth_date'     => $request->input('birth_date'),
               'specialization' => $request->input('specialization'),
               'time_in'        => $request->input('time_in'),
               'time_out'       => $request->input('time_out')
               );

               // Filling the model
               $doctor = Doctor::create($newDoctor);

               // Creating the new user for the doctor
               $user->user      = $doctor->id_number;
               $user->password  = bcrypt('temporal');
               $user->role      = 'doctor';
               $user->doctor_id = $doctor->id;
               $user->save();

               // Return the json response
               return response()->json([
                  'result' => 'success', 
                  'msg' => '<strong> Success!!!</strong> Doctor created successfully.', 
                  'row' => Doctor::where('id', $doctor->id)->with('user')->get()
               ]);

            case 'nurse':
               // Validating the fields
               $this->validate($request, [
               'name'           => 'required',
               'id_number'      => 'required|unique:nurses',
               'birth_date'     => 'required|date',
               'shift'          => 'required',
               ]);

               // Array of doctor data
               $newNurse = array(
               'name'           => $request->input('name'),
               'id_number'      => $request->input('id_number'),
               'birth_date'     => $request->input('birth_date'),
               'shift'          => $request->input('shift')
               );

               // Filling the model
               $nurse = Nurse::create($newNurse);

               if($request->input('beds')) {
                  $nurse->beds()->sync(json_decode($request->input('beds'),1));
               }

               // Return the json response
               return response()->json([
                  'result' => 'success', 
                  'msg' => '<strong> Success!!!</strong> Nurse created successfully.', 
                  'row' => Nurse::where('id', $nurse->id)->with('beds')->get()
               ]);
            
            default:
               return response()->json(['result' => 'error', 'msg' => 'Undefined staff member.']);
         }
      } else {
         return response('Unauthorized.', 401);
      }
   }

   /**
   * Update the specified resource in storage.
   *
   * @param  int  $id
   * @return Response
   */
   public function update(Request $request, $id) {
      // Only admit ajax request and admin user
      if ($request->ajax() && Auth::user()->role === 'master') {
         switch ($request->input('type')) {
            case 'doctor':
               // Doctor object
               $doctor = Doctor::find($id);
               
               // Validating
               $this->validate($request, [
               'name'           => 'required',
               'id_number'      => 'required|unique:doctors,id_number,'.$id,
               'birth_date'     => 'required|date',
               'specialization' => 'required',
               'time_in'        => 'required',
               'time_out'       => 'required|different:time_in'
               ]);

               // Updating user id_number if needed
               if($doctor->id_number != $request->input('id_number')) {
                  $user = User::where('doctor_id', $id)->get()->first();
                  $user->user = $request->input('id_number');
                  $user->save();
               }

               // Updating doctor information
               $doctor->name           = $request->input('name');
               $doctor->id_number      = $request->input('id_number');
               $doctor->birth_date     = $request->input('birth_date');
               $doctor->specialization = $request->input('specialization');
               $doctor->time_in        = $request->input('time_in');
               $doctor->time_out       = $request->input('time_out');
               $doctor->save();

               // return a json response
               return response()->json([
                  'result' => 'success', 
                  'msg' => '<strong> Success!!!</strong> Doctor updated successfully.', 
                  'row' => $doctor
               ]);

            case 'nurse':
               #TO DO:
               // Beds

               // Validating the fields
               $this->validate($request, [
               'name'           => 'required',
               'id_number'      => 'required|unique:nurses,id_number,'.$id,
               'birth_date'     => 'required|date',
               'shift'          => 'required',
               ]);

               $nurse             = Nurse::find($id);
               $nurse->name       = $request->input('name');
               $nurse->id_number  = $request->input('id_number');
               $nurse->birth_date = $request->input('birth_date');
               $nurse->shift      = $request->input('shift');
               $nurse->save();

               if($request->input('beds')) {
                  $nurse->beds()->sync(json_decode($request->input('beds'),1));
               }

               // Return the json response
               return response()->json([
                  'result' => 'success', 
                  'msg' => '<strong> Success!!!</strong> Nurse updated successfully.', 
                  'row' => Nurse::where('id', $id)->with('beds')->get()->first()
               ]);
               break;
            
            default:
               return response()->json(['result' => 'error', 'msg' => 'Undefined staff member.']);
               break;
         }
      } else {
         return response('Unauthorized.', 401);
      }
   }

   /**
   * Remove the specified resource from storage.
   *
   * @param  int  $id
   * @return Response
   */
   public function destroy(Request $request, $id) {
      // Only admit ajax request and admin user
      if ($request->ajax() && Auth::user()->role === 'master') {
         switch ($request->input('type')) {
            case 'doctor':
               // Retrieving the doctor and make him unavaible
               $doctor = Doctor::find($id);
               $doctor->active = 0;
               $doctor->save();

               return response()->json(['result' => 'success', 'msg' => 'Doctor removed successfully.']);

            case 'nurse':
               // If nurse have a bed assigned, set null by the foreign key cascade method
               if(Nurse::destroy($id)) {
                  return response()->json(['result' => 'success', 'msg' => 'Nurse removed successfully.']);
               } else {
                  return response()->json(['result' => 'error', 'msg' => 'Oops, try again.']);
               }
            
            default:
               return response()->json(['result' => 'error', 'msg' => 'Undefined staff member.']);
         }
      } else {
         return response('Unauthorized.', 401);
      }
   }
}