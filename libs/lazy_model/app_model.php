<?php
App::import('Lib', 'LazyModel');
class AppModel extends LazyModel {
  
  function beforeValidate() {
    
    // get empty values for section
    foreach ( $this->data[$this->name] as $key => $value ) {
      $this->validate[$key] = array(
        'rule' => 'notEmpty',
        'message' => '   '. $key .' can not be left empty',
      );
    }
    $this->validationRules();    
  }
  
  function validationRules() {
    switch ( $this->name ) {
      // start Facility
      case 'Facility':
      case 'Resident':
        unset ($this->validate);
        break;
      // start Section A
      case 'SectionA':
        unset($this->validate['A0500B']);
        unset($this->validate['A0500D']);
        unset($this->validate['A1700']);
        // get the age
        $age  = date("Y") - $this->data['SectionA']['A0900']['year'];        
        $b_month = $this->data['SectionA']['A0900']['month'];
        $b_day = $this->data['SectionA']['A0900']['day'];
        $c_month = date('n');
        $c_day = date('t');
        if ($c_month >= $b_month && $c_day >= $b_day) $age = $age - 1;
        // set which rules do not need to be followed
        if ($this->data['SectionA']['A0200'] == 1)     
          unset($this->validate['A0310D']);
        if (isset($this->data['SectionA']['A1100A']) && $this->data['SectionA']['A1100A'] != 1)    
          unset($this->validate['A1100B']);
        if ($this->data['SectionA']['A0310A'] != '01') 
          unset($this->validate['A1500']);
        if (($age >= 22 && $this->data['SectionA']['A0310A'] != '01') || ($age <= 21 && $this->data['SectionA']['A0310A'] != '01' && $this->data['SectionA']['A0310A'] != '03' && $this->data['SectionA']['A0310A'] != '04' && $this->data['SectionA']['A0310A'] != '05'))
          unset($this->validate['A1550']);
        if (!strrpos('10,11,12', $this->data['SectionA']['A0310F']))
          unset($this->validate['A2000']);
        if (!strrpos('10,11,12', $this->data['SectionA']['A0310F']))
          unset($this->validate['A2100']);
        if (!strrpos('05,06', $this->data['SectionA']['A0310A']))
          unset($this->validate['A2200']);
        if ($this->data['SectionA']['A2400A'] == 0) {
          unset($this->validate['A2400B']);
          unset($this->validate['A2400C']);
        }
        break;
      // Start Section B
      case 'SectionB':
        if ($this->data['SectionB']['B0100'] == 1) $this->validate = array();
        break; 
      // Start Section C
      case 'SectionC':
        if ($this->data['SectionC']['C0100'] == 0) {
          unset($this->validate['C0200']);
          unset($this->validate['C0300A']);
          unset($this->validate['C0300B']);
          unset($this->validate['C0300C']);
          unset($this->validate['C0400A']);
          unset($this->validate['C0400B']);
          unset($this->validate['C0400C']);
          unset($this->validate['C0500']);
        }     
        if ($this->data['SectionC']['C0600'] == 0) {
          unset($this->validate['C0700']);
          unset($this->validate['C0900']);
          unset($this->validate['C1000']);
          unset($this->validate['C1300A']);
          unset($this->validate['C1300B']);
          unset($this->validate['C1300C']);
          unset($this->validate['C1300D']);
          unset($this->validate['C1600']);
        }     
        break;
      case 'SectionD':
        // skip if D0100 == 1
        if ($this->data['SectionD']['D0100'] == 0) {
          unset($this->validate['D0200A1']); unset($this->validate['D0200A2']);
          unset($this->validate['D0200B1']); unset($this->validate['D0200B2']);
          unset($this->validate['D0200C1']); unset($this->validate['D0200C2']);
          unset($this->validate['D0200D1']); unset($this->validate['D0200D2']);
          unset($this->validate['D0200E1']); unset($this->validate['D0200E2']);
          unset($this->validate['D0200F1']); unset($this->validate['D0200F2']);
          unset($this->validate['D0200G1']); unset($this->validate['D0200G2']);
          unset($this->validate['D0200H1']); unset($this->validate['D0200H2']);
          unset($this->validate['D0200I1']); unset($this->validate['D0200I2']);
          unset($this->validate['D0350']);   unset($this->validate['D0300']);
          if ($this->data['SectionD']['D0500I1'] != 1)
            unset($this->validate['D0650']);
          // get score
          $score  = $this->data['SectionD']['D0500A2'] + $this->data['SectionD']['D0500B2'] + $this->data['SectionD']['D0500C2'] + $this->data['SectionD']['D0500D2'];
          $score += $this->data['SectionD']['D0500E2'] + $this->data['SectionD']['D0500F2'] + $this->data['SectionD']['D0500G2'] + $this->data['SectionD']['D0500H2'];
          $score += $this->data['SectionD']['D0500I2'] + $this->data['SectionD']['D0500J2'];
        }
        else {
          if ($this->data['SectionD']['D0200I1'] != 1)
            unset($this->validate['D0350']);
          // get score
          $score  = $this->data['SectionD']['D0200A2'] + $this->data['SectionD']['D0200B2'] + $this->data['SectionD']['D0200C2'] + $this->data['SectionD']['D0200D2'];
          $score += $this->data['SectionD']['D0200E2'] + $this->data['SectionD']['D0200F2'] + $this->data['SectionD']['D0200G2'] + $this->data['SectionD']['D0200H2'];
          $score += $this->data['SectionD']['D0200I2'];
          if (($score < 00 || $score > 27) && $this->data['D0300'] != 99)
            $this->validate['D0300'] = array('rule' => 'notEmpty', 'message' => 'D0300 Must be between 00 and 27');
          // get empty
          $empty  = '';
          if ($this->data['SectionD']['D0200A1'] == 9) { $empty += 1; unset($this->validate['D0200A2']); }
          if ($this->data['SectionD']['D0200B1'] == 9) { $empty += 1; unset($this->validate['D0200B2']); } 
          if ($this->data['SectionD']['D0200C1'] == 9) { $empty += 1; unset($this->validate['D0200C2']); }
          if ($this->data['SectionD']['D0200D1'] == 9) { $empty += 1; unset($this->validate['D0200D2']); }
          if ($this->data['SectionD']['D0200E1'] == 9) { $empty += 1; unset($this->validate['D0200E2']); }
          if ($this->data['SectionD']['D0200F1'] == 9) { $empty += 1; unset($this->validate['D0200F2']); }
          if ($this->data['SectionD']['D0200G1'] == 9) { $empty += 1; unset($this->validate['D0200G2']); }
          if ($this->data['SectionD']['D0200H1'] == 9) { $empty += 1; unset($this->validate['D0200H2']); }
          if ($this->data['SectionD']['D0200I1'] == 9) { $empty += 1; unset($this->validate['D0200I2']); }
          if ($empty >= 3)
            $this->validate['D0300'] = array('rule' => 'notEmpty', 'message' => 'Since 3 or more items are empty D0300 must be 99');
          
          // unvalidate skipped
          unset($this->validate['D0500A1']); unset($this->validate['D0500A2']);
          unset($this->validate['D0500B1']); unset($this->validate['D0500B2']);
          unset($this->validate['D0500C1']); unset($this->validate['D0500C2']);
          unset($this->validate['D0500D1']); unset($this->validate['D0500D2']);
          unset($this->validate['D0500E1']); unset($this->validate['D0500E2']);
          unset($this->validate['D0500F1']); unset($this->validate['D0500F2']);
          unset($this->validate['D0500G1']); unset($this->validate['D0500G2']);
          unset($this->validate['D0500H1']); unset($this->validate['D0500H2']);
          unset($this->validate['D0500I1']); unset($this->validate['D0500I2']);
          unset($this->validate['D0500J1']); unset($this->validate['D0500J2']);
          unset($this->validate['D0600']);   unset($this->validate['D0650']);
          
        }
        break;
      case 'SectionE':
        if ($this->data['SectionE']['E0100A'] == 0 && $this->data['SectionE']['E0100B'] == 0 && $this->data['SectionE']['E0100Z'] != 1) {
          $this->data['SectionE']['E0100Z'] = ''; 
          $this->validate['E0100Z'] = array('rule' => 'notEmpty', 'message' => 'E0100Z. You must select this option, if no other options selected');
        }
        if ($this->data['SectionE']['E0300'] == 0) {
          unset($this->validate['E0500A']); unset($this->validate['E0500B']); unset($this->validate['E0500C']);
          unset($this->validate['E0600A']); unset($this->validate['E0600B']); unset($this->validate['E0600C']);
        }
        if ($this->data['SectionE']['E0900'] == 0) {
          unset($this->validate['E1000A']); unset($this->validate['E1000B']); 
        }
        break;
        
      case 'SectionF':
        if ($this->data['SectionF']['F0300'] == 0) {
          foreach ($this->data['SectionF'] as $key => $value) {
            if (preg_match('|F0400|', $key) !== false) unset($this->validate[$key]);
            if (preg_match('|F0500|', $key) !== false) unset($this->validate[$key]);
          }
          unset($this->validate['F0600']);
        }
        // check section F0800
        if ($this->data['SectionF']['F0600'] == 9) {
          $empty = 0;
          foreach ($this->data['SectionF'] as $key => $value) {
            if (preg_match('|F0800|', $key) !== false && $value == 0) $empty += 0;
            if (preg_match('|F0800|', $key) !== false && $value == 1) $empty += 1;
          }
          if ($empty == 0) {
            $this->data['SectionF']['F0800Z'] = '';
            $this->validate['F0800Z'] = array('rule' => 'notEmpty', 'message' => 'E0100Z. You must select this option, if no other options selected');
          }
        }
        else {
          foreach ($this->data['SectionF'] as $key => $value) {
            if (preg_match('|F0800|', $key) !== false) unset($this->validate[$key]);
            if (preg_match('|F0700|', $key) !== false) unset($this->validate[$key]);
          }
        } 
        break;
        
      case 'SectionG':
        unset($this->validate['A0310A']);
        if ($this->data['SectionG']['A0310A'] != '01') {
          unset($this->validate['G0900A']);
          unset($this->validate['G0900B']);
        }
        break;
      
      case 'SectionH':
        if (isset($this->data['SectionH']['H0200A']) && $this->data['SectionH']['H0200A'] == 0) {
          unset($this->validate['H0200B']);
          unset($this->validate['H0200C']);
        }
        if ($this->data['SectionH']['H0200A'] == 9) {
          unset($this->validate['H0200B']);
        }
        break;
      
      case 'SectionI':
        foreach ( $this->data['SectionI'] as $key => $value ) {
          if (preg_match('|I8000|', $key) !== false) unset($this->validate[$key]);
        }
        if (isset($this->data['SectionI']['I7900']) && $this->data['SectionI']['I7900'] == 1) {
          foreach ( $this->data['SectionI'] as $key => $value ) {
            unset($this->validate[$key]);
          }
        }
        
        break;
      
      case 'SectionJ':
        if (!empty($this->data['SectionJ']['J0200']) && $this->data['SectionJ']['J0200'] == 0) {
          foreach ( $this->data['SectionJ'] as $key => $value ) {
            if (preg_match('|J0400|', $key)) unset($this->validate[$key]);
            if (preg_match('|J0500|', $key)) unset($this->validate[$key]);
            if (preg_match('|J0600|', $key)) unset($this->validate[$key]);
            if (preg_match('|J0700|', $key)) unset($this->validate[$key]);
          }
        }
        if (isset($this->data['SectionJ']['J0300'])) {
          switch($this->data['SectionJ']['J0300']) {
            case 0:
              unset($this->validate['J0400']); 
              unset($this->validate['J0500A']); unset($this->validate['J0500B']); 
              unset($this->validate['J0600A']); unset($this->validate['J0600B']); 
              unset($this->validate['J0700']); 
              unset($this->validate['J0800A']); unset($this->validate['J0800B']); unset($this->validate['J0800C']); unset($this->validate['J0800D']); unset($this->validate['J0800Z']); 
              unset($this->validate['J0850']); 
              break;
            case 9:
              unset($this->validate['J0400']); 
              unset($this->validate['J0500A']); unset($this->validate['J0500B']); 
              unset($this->validate['J0600A']); unset($this->validate['J0600B']); 
              unset($this->validate['J0700']); 
              break;
          }
        }
        if (!empty($this->data['SectionJ']['J0600A']) || !empty($this->data['SectionJ']['J0600B'])) {
          unset($this->validate['J0600A']); unset($this->validate['J0600B']); 
        }
        if ($this->data['SectionJ']['J0400'] == 1 || $this->data['SectionJ']['J0400'] == 2 || $this->data['SectionJ']['J0400'] == 3 || $this->data['SectionJ']['J0400'] == 4 )
          $this->data['SectionJ']['J0700'] = 0;
        else
          $this->data['SectionJ']['J0700'] = 1; 
        if ($this->data['SectionJ']['J0700'] == 0) {
          foreach ( $this->data['SectionJ'] as $key => $value ) {
            if (preg_match('|J0800|', $key)) unset($this->validate[$key]);
            
          }
          unset($this->validate['J0850']);
        }
        if ($this->data['SectionJ']['J1800'] == 0) {
          unset($this->validate['J1900A']); unset($this->validate['J1900B']); unset($this->validate['J1900C']); 
        }
        break;
             
      case 'SectionK':
        if ($this->data['SectionK']['K0500A'] != 1 || $this->data['SectionK']['K0500B'] != 1) {
          unset($this->validate['K0700A']);
          unset($this->validate['K0700B']);
        }
        break; 
        
      case 'SectionM':
        if ($this->data['SectionM']['M0210'] == 0) {
          foreach ( $this->data['SectionM'] as $key => $value ) {
            if (preg_match('|M0300|', $key)) unset($this->validate[$key]);
            if (preg_match('|M0610|', $key)) unset($this->validate[$key]);
            if (preg_match('|M0700|', $key)) unset($this->validate[$key]);
            if (preg_match('|M0800|', $key)) unset($this->validate[$key]);
            if (preg_match('|M0900|', $key)) unset($this->validate[$key]);
          }          
        }
        if ($this->data['SectionM']['M0300B1']) {
          unset($this->validate['M0300B2']);
          unset($this->validate['M0300B3']);
        }
        if ($this->data['SectionM']['M0300C1']) {
          unset($this->validate['M0300C2']);
        }
        if ($this->data['SectionM']['M0300D1']) {
          unset($this->validate['M0300D2']);
          unset($this->validate['M0300D3']);
        }
        if ($this->data['SectionM']['M0300E1']) {
          unset($this->validate['M0300E2']);
        }
        if ($this->data['SectionM']['M0300F1']) {
          unset($this->validate['M0300F2']);
        }
        if ($this->data['SectionM']['M0300G1']) {
          unset($this->validate['M0300G2']);
        }
        if ($this->data['SectionM']['M0300C1'] > 0 || $this->data['SectionM']['M0300D1'] > 0 || $this->data['SectionM']['M0300F1'] > 0)
          unset($this->validate['M0610']);
        if ($this->data['SectionM']['A0310E'] != 0) {
          unset($this->validate['M0800']);
          unset($this->validate['M0900']);
        }
        break;
      
      case 'SectionN':
        if ($this->data['SectionN']['N0300'] == 0) {
          foreach ( $this->data['SectionN'] as $key => $value ) {
            if (preg_match('|N0350|', $key)) unset($this->validate[$key]);
            if (preg_match('|N0400|', $key)) unset($this->validate[$key]);
          }
        }
        break;
      
      case 'SectionO':
        foreach ( $this->data['SectionO'] as $key => $value ) {
          unset($this->validate[$key]);
        } 
        break;
      
      case 'SectionQ':
        if ($this->data['SectionQ']['A0310E'] != 1)
          foreach ( $this->data['SectionQ'] as $key => $value ) {
            if (preg_match('|Q0300|', $key)) unset($this->validate[$key]);
          }
        if ($this->data['SectionQ']['Q0400A'] == 1)
          foreach ( $this->data['SectionQ'] as $key => $value ) {
            if (preg_match('|Q0500|', $key)) unset($this->validate[$key]);
          }
        if ($this->data['SectionQ']['Q0500A'] == 2)
          unset($this->validate['Q0500B']);
        break;
      
      case 'SectionV':
        if ($this->data['SectionV']['A0310E'] == 1) {
          foreach ( $this->data['SectionV'] as $key => $value ) {
            if (preg_match('|V0100|', $key)) unset($this->validate[$key]);
          }
        }
        foreach ( $this->data['SectionV'] as $key => $value ) {
          if (preg_match('|V0200|', $key)) unset($this->validate[$key]);
        }
        break;
      
      case 'SectionX':
        if ($this->data['SectionX']['X0100'] == 1) {
          foreach ( $this->data['SectionX'] as $key => $value ) {
            unset($this->validate[$key]);
          }
        }
        if ($this->data['SectionX']['X0150'] != 2) 
          unset($this->validate['X0600D']);
        if ($this->data['SectionX']['X0600F'] == 99) {
          unset($this->validate['X0700B']);
          unset($this->validate['X0700C']);
        }
        if (strpos($this->data['SectionX']['X0600F'], '10,11,12')) {
          unset($this->validate['X0700A']);
          unset($this->validate['X0700C']);
        }
        if ($this->data['SectionX']['X0600F'] == '01') {
          unset($this->validate['X0700A']);
          unset($this->validate['X0700B']);
        }
        if ($this->data['SectionX']['X0100'] != 2) {
          foreach ( $this->data['SectionX'] as $key => $value ) {
            if (strpos($key, 'X0900')) unset($this->validate[$key]);
          }
        }
        if ($this->data['SectionX']['X0100'] != 3) {
          foreach ( $this->data['SectionX'] as $key => $value ) {
            if (strpos($key, 'X1050')) unset($this->validate[$key]);
          }
        }
        break;
      
      case 'SectionZ':
        foreach ( $this->data['SectionZ'] as $key => $value ) {
          unset($this->validate[$key]);
        } 
        $this->validate['Z0100C'] = array(
        'rule' => 'notEmpty',
        'message' => 'You must select if this is a Medicare Short Stay as defined in RAI Manual 6-22',
      );
        break;
        
      case 'Assessment':
        foreach ( $this->data['Assessment'] as $key => $value ) {
          unset($this->validate[$key]);
        } 
        break;
        
      case 'Change':
        unset($this->validate['id']);
        break;
    }
  }
}
?>