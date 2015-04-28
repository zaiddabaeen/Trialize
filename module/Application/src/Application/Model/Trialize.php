<?php

namespace Application\Model;

class Trialize {

 public $id, $app_name, $package_name, $trial_end, $enabled, $launch_count, $last_launched, $created_at;

 public function exchangeArray($data) {

  $this->id = (isset($data['id'])) ? $data['id'] : null;
  $this->app_name = (isset($data['app_name'])) ? $data['app_name'] : null;
  $this->package_name = (isset($data['package_name'])) ? $data['package_name'] : null;
  $this->trial_end = (isset($data['trial_end'])) ? $data['trial_end'] : null;
  $this->enabled = (isset($data['enabled'])) ? $data['enabled'] : null;
  $this->launch_count = (isset($data['launch_count'])) ? $data['launch_count'] : null;
  $this->last_launched = (isset($data['last_launched'])) ? $data['last_launched'] : null;
  $this->created_at = (isset($data['created_at'])) ? $data['created_at'] : null;
  
 }

}
