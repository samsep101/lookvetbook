<?php
    class DateTest extends PHPUnit_Framework_TestCase
    {
        /**
         * @dataProvider ageProvider
         */
        public function testGetAge($date, $age)
        {
            $this->assertEquals(Date::getAge($date), $age);
        }

        public function ageProvider()
        {
            return array(
                array('1989-11-12', 24),
                array('1988-11-12', 25),
            );
        }

        /**
         * @dataProvider wordProvider
         */
        public function testAgeWord($age, $word)
        {
            $this->assertEquals(Date::getAgeWord($age), $word);
        }

        public function wordProvider()
        {
            return array(
                array(23, 'года'),
                array(99, 'лет'),
                array(12, 'лет'),
                array(21, 'год'),
                array(15, 'лет'),
            );
        }


        /**
         * @dataProvider ageWithWordProvider
         */
        public function testAgeWithWord($date, $age_with_word)
        {
            $this->assertEquals(Date::getAge($date, TRUE), $age_with_word);
        }

        public function ageWithWordProvider()
        {
            return array(
                array('1989-11-12', '23 года'),
                array('1988-11-12', '24 года'),
            );
        }

    }