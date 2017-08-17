<?php

class ImportController extends BaseController
{
    /**
     * Получение всех или конкретных клиник по docdoc_id
     * @param type $docdoc_ids
     * @return type
     */
    private function _getClinics($docdoc_ids = false) {

        $manager = new ClinicManager();

        if($docdoc_ids) {
            return $manager->getListWithDocdocIdList($docdoc_ids);
        }

        return $manager->getListWithDocdocId();
    }

    /**
     * Процесс импорта
     */
    public function docdoc()
    {
        /** @var Db $db */
        $db = Register::get('db');
        $insert = 0;
        $update = 0;
        $notfaund = 0;
        $clinics = $this->_getClinics();
        //$clinics = $this->_getClinics([249, 1497]);
        $clinic_data_url = 'https://lookmedbook:IzkmbB@back.docdoc.ru/api/rest/1.0.6/json/clinic/';
        $doctor_data_url = 'https://lookmedbook:IzkmbB@back.docdoc.ru/api/rest/1.0.6/json/doctor/';
        foreach ($clinics as $clinic){
#if ($clinic->id != 3819)continue;

        echo "clinic: $clinic->name<br>".PHP_EOL;
        try{
		echo $clinic_data_url.$clinic->docdoc_id.PHP_EOL;
            $s = file_get_contents($clinic_data_url.$clinic->docdoc_id);
            $data = json_decode($s);
            //var_dump($data); exit();
            $data = $data->Clinic[0];
        }catch (Exception $exp){
            continue;
        }

            // импорт адреса
            if(!empty($data->Street) AND !empty($data->House) AND !empty($data->StreetId)){
                $clinic->address = $data->Street.', '.$data->House;
                $clinic->street_id = (int)$data->StreetId;
            }
            if(!empty($data->Latitude) AND !empty($data->Longitude)){
                $clinic->latitude = $data->Latitude;
                $clinic->longitude = $data->Longitude;
            }
            $clinic->about = $data->Description;

            $clinicId = $clinic->id;

            $clinic->save();

            $metro = $data->Stations;

            /** upadating metro start **/

            $metroStationToClinic =  (new MetroStationToClinicManager()) ->deleteByClinicId($clinicId);

            foreach( $metro as $m ){

                $metroBranch  = ( new MetroBranchManager() )->getListByIds( array($m->LineId) );
                if( !empty ( $metroBranch ) ){

                     $metroStation = ( new MetroStationManager() )->getListByName($m->Name);

                    if( !empty( $metroStation ) ){

                        $metroStationId = $metroStation[0]->id;

                        $metroStation = (new MetroStationManager())->getOneById($metroStation[0]->id);

                        if($metroStationToClinic === "insert"){ $insert++; }else{ $update++; }

                    }else{

                        $metroStation = new MetroStationModel();

                    }

                    $metroStation->name =            $m->Name;
                    $metroStation->metro_branch_id = $m->LineId;
                    $metroStation->longitude =       $m->Longitude;
                    $metroStation->latitude =        $m->Latitude;
                    $metroStation->save();
                    $metroStationId = $metroStation->id;

                    $metroStationToClinic = new MetroStationToClinicModel();
                    $metroStationToClinic->clinic_id = $clinicId;
                    $metroStationToClinic->metro_station_id = $metroStationId;
                    $metroStationToClinic->save();

                }

            }

            $clinic_specialty = [];

            $q = "delete from doctor_to_clinic where clinic_id='$clinic->id'";
            $db->query($q);

			$q = "delete from doctor_specialty_to_clinic where clinic_id='$clinic->id'";
            $db->query($q);

            foreach ($data->Doctors as $doc_id){
                try{
					echo $doctor_data_url.$doc_id."<br>".PHP_EOL;
                    $s = file_get_contents($doctor_data_url.$doc_id.'/withSlots/1');
                    $docdata = json_decode($s);
                    $docdata = $docdata->Doctor[0];
                    //var_dump($docdata); exit;
                }catch (Exception $exp){
                    continue;
                }

                list($last_name, $first_name, $second_name) = explode(' ', $docdata->Name);
//                if ($docdata->Slots)
//                    pr($docdata->Slots, 1);

                # Checking if the doctor was deleted
                $deletedDoctor =  ( new DeletedDoctorManager() )->getListByQuery("
                    SELECT * FROM deleted_doctor
                    WHERE last_name LIKE \"{$last_name}\"
                    AND first_name LIKE \"{$first_name}\"
                    AND second_name LIKE \"$second_name\" ");

                if( empty( $deletedDoctor) ){

                    $q = "select doctor.id
                          from doctor
                          where
                          doctor.last_name like '$last_name' and
                          doctor.first_name like '$first_name' and
                          doctor.second_name like '$second_name' ";

                    if ($a = $db->query($q)){
                        $doctor = (new DoctorManager())->getOneById($a[0]['id']);
                        echo "doctor: $doctor->full_lower_name ($doctor->id)<br>".PHP_EOL;
                    }else{
                        $doctor = new DoctorModel();
                        echo "new doctor: $docdata->Name<br>".PHP_EOL;
                    }

                    $doctor->last_name = $last_name;
                    $doctor->first_name = $first_name;
                    $doctor->second_name = $second_name;
                    $price = $docdata->Price > 0 ? (float) $docdata->Price : '';

                    $doctor->full_lower_name = strtolower($docdata->Name);
                    $doctor->sex_id = ($docdata->Sex == 1) ? 2 : 1;
                    $doctor->rate = (float) $docdata->Rating;
                    $doctor->work_experience = 0;
                    $doctor->advice_rate = (float) $docdata->Rating;
                    $doctor->cabinet_rate = (float) $docdata->Rating;
                    $doctor->relationship_rate = (float) $docdata->Rating;
                    $doctor->value_for_money_rate = (float) $docdata->Rating;
                    $doctor->diagnosis_is_clear_rate = (float) $docdata->Rating;
                    $doctor->about = $docdata->Description;
                    $doctor->is_active = 1;
                    $doctor->availability = 1;
                    $doctor->is_has_morning_time = 1;
                    $doctor->is_has_evening_time = 1;
                    $doctor->is_has_weekend_time = 1;
                    $doctor->is_leave_the_house = 1;
                    $doctor->is_children = 1;
                    $doctor->is_adult = 1;
                    $doctor->is_pregnant = 1;
                    $doctor->is_handicapped = 1;
                    $doctor->save();

                    $q = "select doctor.id
                          from doctor
                          where
                          doctor.last_name like '$last_name' and
                          doctor.first_name like '$first_name' and
                          doctor.second_name like '$second_name' ";

                    if ($a = $db->query($q)){
                        $doctor = (new DoctorManager())->getOneById($a[0]['id']);
                    }else{
                        echo "error doc add: $docdata->Name<br>".PHP_EOL;
                        continue;
                    }

                    foreach ($docdata->Specialities as $specialty){

                        $t = (new SpecialtyManager())->getOneByAliasOrSyninim($specialty->Alias);
                        if (!$t){
                            echo "<span style='color:red'>specialty $specialty->Name ($specialty->Alias) not found</span><br>".PHP_EOL;
                            continue;
                        }

                        $specialty = $t;
                        $clinic_specialty[$specialty->id] = $specialty;


                        $q = "select id from doctor_to_clinic where clinic_id='$clinic->id' and doctor_id='$doctor->id' and specialty_id='$specialty->id'";
                        if (!$db->query($q)){
                            $q = "insert into doctor_to_clinic set clinic_id='$clinic->id', doctor_id='$doctor->id', specialty_id='$specialty->id'". ($price ? " , first_visit_price='$price'" : '');
                            $db->query($q);
                        }

                        $q = "insert into doctor_specialty_to_clinic set clinic_id='$clinic->id', doctor_id='$doctor->id', specialty_id='$specialty->id'";
                        $db->query($q);
                    }

                    if ($docdata->Img){
                        $tmp_name = ABS_ROOT.'/media/upload/clinic/license/tmp_doctor_'.$doctor->id.'.jpg';
                        echo "tmp file name:".$tmp_name."<br>".PHP_EOL;
                        if (file_exists($tmp_name))
                        {
                            unlink($tmp_name);
                        }

                        // продолжаем процесс только если картинка сохранена
                        if(false !== file_put_contents($tmp_name, file_get_contents($docdata->Img))){

                            chmod($tmp_name , 0755);

                            $image_id = ImageUploader::upload(['upload_folder' => 'clinic/license/'], ['name' => 'doctor_'.$doctor->id.'.jpg', 'tmp_name' => $tmp_name], 'doctor_'.$doctor->id);

                            $doctor->image_id = $image_id;
                            $doctor->card_image_id = $image_id;
                            $doctor->save();

                            if (file_exists($tmp_name))
                            {
                                unlink($tmp_name);
                            }

                            $q = "delete from image_to_doctor where doctor_id=$doctor->id";
                            $db->query($q);

                            $q = "insert into image_to_doctor set doctor_id=$doctor->id, image_id=$image_id";
                            $db->query($q);
                        }
                    }
                }
            }
            $specialty_ids = [];
            foreach ($clinic_specialty as $specialty){
                $specialty_ids[$specialty->id] = $specialty->id;
            }

			if ($specialty_ids){
				$q = "select DISTINCT specialization_id from specialty_to_specialization where specialty_id in (".implode(',', $specialty_ids).")";
				$specializations = $db->query($q);
				foreach ($specializations as $specialization){
					$specialization_id = $specialization['specialization_id'];

					if (!$specialization_id)
						continue;

					$q = "select id from specialization_to_clinic where clinic_id='$clinic->id' and specialization_id='$specialization_id'";
					if (!$db->query($q)){
						$q = "insert into specialization_to_clinic set clinic_id='$clinic->id', specialization_id='$specialization_id'";
						$db->query($q);
					}
				}
			}

            flush();

            echo "UPDATE : {$update} ; INSERT : {$insert} ; NOT FOUND: {$notfaund}".PHP_EOL;
        }

        die('<br>finish<br>');
    }


    public function update_clinic()
    {
        $update = "";
        $new    = "";
        $path   = __DIR__.'/../../media/upload/clinic/';
        if( ( $sh = fopen ( $path."clinic.csv", "r" ) ) !== FALSE ){

            while ( ($data = fgetcsv( $sh, 1000, ";" )) !== FALSE ){
                echo "______________________________".PHP_EOL;

                $clinic = ( new ClinicManager() )->getOneById( (int) $data[0] );
                if(!empty ( $clinic ) ){

                    $clinic->name = $data[1];
                    $clinic->save();
                    $update ++;

                }else{

                    $new ++;
                }

            }

            fclose( $sh );
        }
        echo $update. ": ". $new.PHP_EOL;
        die(" ready ");
    }
}
