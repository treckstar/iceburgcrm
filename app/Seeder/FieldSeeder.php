<?php

namespace App\Seeder;

use App\Models\Field;
use Faker\Factory;
use Illuminate\Support\Facades\DB as DB;

class FieldSeeder {
    private $module;
    public function __construct($module)
    {
        $this->module=$module;
    }

    public function seed($seed) : void
    {
        $faker = Factory::create();

        $this->createDiskSeed(20, 'image');
        $this->createDiskSeed(20, 'video');
        $this->createDiskSeed(20, 'file');

        for($x=0;$x<$seed;$x++) {

            $data = Field::factory()->make()->toArray();
            $data = ['slug' => $faker->regexify('[A-Za-z0-9]{20}')];
            $this->module->fields()->get()->each(function ($field) use (&$data, $faker) {

                if (!empty($field->list)) {
                    $data[$field->name] = 1;
                } else {

                    switch($field->input_type)
                    {
                        case 'color':
                            $data[$field->name] = $faker->hexColor();
                            break;
                        case 'tel':
                            $data[$field->name] = $faker->phoneNumber;
                            break;
                        case 'email':
                            $data[$field->name] = $faker->email;
                            break;
                        case 'city':
                            $data[$field->name] = $faker->city;
                            break;
                        case 'zip':
                            $data[$field->name]  = $faker->postcode;
                            break;
                        case 'address':
                            $data[$field->name]  = $faker->streetAddress();
                            break;
                        case 'checkbox':
                            $data[$field->name] = (bool) rand(0, 1);
                            break;
                        case 'file':
                        case 'video':
                        case 'image':
                            $data[$field->name] = rand(1, 20);
                            break;
                        case 'password':
                            $data[$field->name] = $faker->password();
                            break;
                        case 'number':
                            $data[$field->name] = rand(1, 2000);
                            break;
                        case 'url':
                            $data[$field->name] = $faker->url();
                            break;
                        case 'date':
                            $data[$field->name] = rand(147446502, 1667446502);
                            break;
                        case 'currency':
                            $data[$field->name] = $faker->randomFloat(2, 1, 100);
                            break;
                        case 'related':
                            $data[$field->name] = rand(1, 5);
                            break;
                        case 'textarea':
                            $data[$field->name] = $faker->realTextBetween(50, 200);
                            break;
                        default:
                            if ($field->name == 'name') {
                                $data[$field->name] = $faker->company;
                            }
                            elseif ($field->name == 'first_name') {
                                $data[$field->name] = $faker->firstName;
                            }
                            elseif ($field->name == 'last_name') {
                                $data[$field->name] = $faker->lastName;
                            }
                            elseif ($field->data_type == 'string') {
                                $data[$field->name] = $faker->realTextBetween(10, 50);
                            } elseif ($field->data_type == 'Integer') {
                                $data[$field->name] = $faker->numberBetween(1, 100);
                            } else {
                                $data[$field->name] = 1;
                            }
                        break;
                    }
                }
            });
            $data['created_at']=date('Y-m-d H:i:s', strtotime("-" . rand(1, 31) . " DAY"));
            $data['updated_at']=$data['created_at'];
            DB::table($this->module->name)->insert($data);
        }
    }

    private function createDiskSeed($amount=20, $type='') : void {

    }

}