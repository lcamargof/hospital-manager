<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class MasterTest extends TestCase {
    use DatabaseTransactions;

    // Login the master user and do the actions
    public function testMasterActions() {
        // Not logged
        $this->assertFalse(Auth::check());

        // Login as receptionist
        $this->visit('/')
            ->type('reception', 'user')
            ->type('test', 'password')
            ->press('Login')
            ->seePageIs('/home')
            ->assertTrue(Auth::check() && Auth::user()->user == 'reception' && Auth::user()->role == 'receptionist');

        // Redirect to home if logged, and logout
        $this->visit('/')
            ->seePageIs('/home')
            ->visit('/logout')
            ->seePageIs('/')
            ->assertFalse(Auth::check());

        // Enter the login page as master and log
        $this->visit('/')
            ->type('master', 'user')
            ->type('test', 'password')
            ->press('Login')
            ->seePageIs('/home')
            ->assertTrue(Auth::check() && Auth::user()->user == 'master' && Auth::user()->role == 'master');

        // Disabling filters
        $this->withoutMiddleware();

        // Staff operations
        $this->staffScrub();

        // Bed operations
        $this->bedScrub();

        // Users operations
        $this->userScrub();

        // Finally logout
        $this->visit('/logout')
            ->seePageIs('/')
            ->assertFalse(Auth::check());
    }

    // Staff scrub operations
    public function staffScrub() {
        /**
        *
        * Doctor TEST
        *
        **/

        // Add a doctor
        $this->post('/staff', [
            'type' => 'doctor',
            'name' => 'Doctor Test',
            'id_number' => '12345',
            'birth_date' => '1980-05-05',
            'specialization' => 'Unit testing',
            'time_in' => '08:00:00',
            'time_out' => '18:00:00'
        ], [
            'HTTP_X-Requested-With' => 'XMLHttpRequest'
        ])->seeJson([
            'result' => 'success',
        ])->seeInDatabase('doctors', [
            'id_number' => '12345'
        ])->seeInDatabase('users', [
            'user' => '12345'
        ]);

        // Get the recent added doctor
        $doctor = App\Doctor::where('id_number', '12345')->get()->first();

        // Edit a doctor
        $this->put('/staff'.'/'.$doctor->id, [
            'type' => 'doctor',
            'name' => 'Doctor Tester',
            'id_number' => '123456789',
            'birth_date' => '1990-05-05',
            'specialization' => 'Unit testingggggg',
            'time_in' => '09:00:00',
            'time_out' => '18:00:00'
        ], [
            'HTTP_X-Requested-With' => 'XMLHttpRequest'
        ])->seeJson([
            'result' => 'success',
        ])->seeInDatabase('doctors', [
            'id_number' => '123456789'
        ])->seeInDatabase('users', [
            'user' => '123456789'
        ]);

        // Delete a doctor
        $this->delete('/staff'.'/'.$doctor->id, [
            'type' => 'doctor'
        ], [
            'HTTP_X-Requested-With' => 'XMLHttpRequest'
        ])->seeJson([
            'result' => 'success'
        ])->seeInDatabase('doctors', [
            'id_number' => '123456789',
            'active' => 0
        ]);

        /**
        *
        * Nurse TEST
        *
        **/

        // Add a doctor
        $this->post('/staff', [
            'type' => 'nurse',
            'name' => 'Nurse Test',
            'id_number' => '953654',
            'birth_date' => '1990-10-10',
            'shift' => 'diurn'
        ], [
            'HTTP_X-Requested-With' => 'XMLHttpRequest'
        ])->seeJson([
            'result' => 'success',
        ])->seeInDatabase('nurses', [
            'id_number' => '953654'
        ]);

        // Get the recent added nurse
        $nurse = App\Nurse::where('id_number', '953654')->get()->first();

        // Edit a doctor
        $this->put('/staff'.'/'.$nurse->id, [
            'type' => 'nurse',
            'name' => 'Nurse Test',
            'id_number' => '953654',
            'birth_date' => '1990-10-10',
            'shift' => 'nocturne'
        ], [
            'HTTP_X-Requested-With' => 'XMLHttpRequest'
        ])->seeJson([
            'result' => 'success',
        ])->seeInDatabase('nurses', [
            'id_number' => '953654',
            'shift' => 'nocturne'
        ]);

        // Delete a doctor
        $this->delete('/staff'.'/'.$nurse->id, [
            'type' => 'nurse'
        ], [
            'HTTP_X-Requested-With' => 'XMLHttpRequest'
        ])->seeJson([
            'result' => 'success'
        ])->notSeeInDatabase('nurses', [
            'id_number' => '953654'
        ]);
    }

    // Beds scrub operations
    public function bedScrub() {
        /**
        *
        * Wards TEST
        *
        **/

        // Add a ward
        $this->post('/beds', [
            'type' => 'ward',
            'identification' => 'test01',
            'capacity' => 10
        ], [
            'HTTP_X-Requested-With' => 'XMLHttpRequest'
        ])->seeJson([
            'result' => 'success',
        ])->seeInDatabase('wards', [
            'identification' => 'test01'
        ]);

        // Get the recent added ward
        $ward = App\Ward::where('identification', 'test01')->get()->first();

        // Edit a ward
        $this->put('/beds'.'/'.$ward->id, [
            'type' => 'ward',
            'identification' => 'test01',
            'capacity' => 5
        ], [
            'HTTP_X-Requested-With' => 'XMLHttpRequest'
        ])->seeJson([
            'result' => 'success',
        ])->seeInDatabase('wards', [
            'identification' => 'test01',
            'capacity' => 5
        ]);

        // Delete a ward
        $this->delete('/beds'.'/'.$ward->id, [
            'type' => 'ward'
        ], [
            'HTTP_X-Requested-With' => 'XMLHttpRequest'
        ])->seeJson([
            'result' => 'success'
        ])->notSeeInDatabase('wards', [
            'identification' => 'test01'
        ]);

        /**
        *
        * Bed TEST
        *
        **/

        // Add a bed
        $this->post('/beds', [
            'type' => 'bed',
            'identification' => 'bed-10'
        ], [
            'HTTP_X-Requested-With' => 'XMLHttpRequest'
        ])->seeJson([
            'result' => 'success',
        ])->seeInDatabase('beds', [
            'identification' => 'bed-10'
        ]);

        // Get the recent added bed
        $bed = App\Bed::where('identification', 'bed-10')->get()->first();

        // Edit a bed
        $this->put('/beds'.'/'.$bed->id, [
            'type' => 'bed',
            'identification' => 'bed-05'
        ], [
            'HTTP_X-Requested-With' => 'XMLHttpRequest'
        ])->seeJson([
            'result' => 'success',
        ])->seeInDatabase('beds', [
            'identification' => 'bed-05'
        ]);

        // Delete a bed
        $this->delete('/beds'.'/'.$bed->id, [
            'type' => 'bed'
        ], [
            'HTTP_X-Requested-With' => 'XMLHttpRequest'
        ])->seeJson([
            'result' => 'success'
        ])->notSeeInDatabase('beds', [
            'identification' => 'bed-05'
        ]);
    }

    // Only update the password
    public function userScrub() {
        $receptionUser = App\User::where('user', 'reception')->get()->first();

        // Edit a bed
        $this->put('/users'.'/'.$receptionUser->id, [
            'password' => 'test'
        ], [
            'HTTP_X-Requested-With' => 'XMLHttpRequest'
        ])->see('success');
    }
}

?>
