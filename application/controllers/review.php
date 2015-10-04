<?php

    class ReviewController extends BaseController
    {
        public $layout = 'home';

        public function ajaxAdd()
        {
            if (!Acc::isAuthed())
                $this->redirectUrl('/');

            $visit_id = $this->request->post('visit_id');

            $visit_manager = new VisitManager();
            $visit = $visit_manager->getOneById($visit_id);

            $doctor_id = $visit->doctor_id ? $visit->doctor_id : null;
            $clinic_id = $visit->clinic_id ? $visit->clinic_id : null;

            if ($_POST) {

                $doctor_review_text = $this->request('doctor_review');
                $clinic_review_text = $this->request('clinic_review');
                $private_review_text = $this->request('private_review');

                $visit_rating = new VisitRatingModel();
                $visit_rating->visit_id = $visit_id;
                $visit_rating->clinic_id = $clinic_id;
                $visit_rating->doctor_id = $doctor_id;
                $visit_rating->cabinet = $this->request('cabinet');
                $visit_rating->waiting_time = $this->request('waiting_time');
                $visit_rating->relationship = $this->request('relationship');
                $visit_rating->value_for_money = $this->request('value_for_money');
                $visit_rating->diagnosis_is_clear = $this->request('diagnosis_is_clear');
                $visit_rating->service_at_the_reception = $this->request('service_at_the_reception');
                $visit_rating->is_doctor_advice = $this->request('is_doctor_advice');
                $visit_rating->is_clinic_advice = $this->request('is_clinic_advice');
                $visit_rating->doctor_review_text = $doctor_review_text;
                $visit_rating->clinic_review_text = $clinic_review_text;
                $visit_rating->private_review_text = $private_review_text;
                $visit_rating->is_confirmed = 0;
                $visit_rating->dt = DateHelper::now();
                $visit_rating->account_id = Acc::accountId();

                if (ModelManagerFactory::getByName('visit_rating')->save($visit_rating)) {
                    JsonResponse::result(TRUE);
                } else {
                    JsonResponse::error(31);
                }
            }
        }

    }