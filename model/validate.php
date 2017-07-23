<?php
class Validate {
    private $fields;
    private $orignalPass = "";

    public function __construct() {
        $this->fields = new Fields();

    }

    public function getFields() {
        return $this->fields;
    }

// Validate a generic text field
    public function text($name, $value, $required = true,
                         $min = 1, $max = 255) {
        // Get Field object
        $field = $this->fields->getField($name);

        // If not required and empty, clear errors
        if (!$required && empty($value)) {
            $field->clearErrorMessage();
            return;
        }
        // Check field and set or clear error message
        if ($required && empty($value)) {
            $field->setErrorMessage('Required.');
        } else if (strlen($value) < $min) {
            $field->setErrorMessage('Too short.');
        } else if (strlen($value) > $max) {
            $field->setErrorMessage('Too long.');
        } else {
            $field->clearErrorMessage(); }
    }

    public function phone($name, $value,$required = false)
    {
        $field = $this->fields->getField($name);

        // Call the text method
        // and exit if it yields an error
        $this->text($name, $value, $required);
        if ($field->hasError()) { return; }

        // Call the pattern method
        // to validate a phone number
        $pattern =
        '/^[[:digit:]]{3}-[[:digit:]]{3}-[[:digit:]]{4}$/';

        $message = 'Invalid phone number.';
        $this->pattern(
            $name, $value, $pattern, $message, $required);
    }
 public function password($name, $value,$required = false) {
        $field = $this->fields->getField($name);

        // Call the text method
        // and exit if it yields an error
        $this->text($name, $value, $required);
        if ($field->hasError()) { return; }

        // Call the pattern method
        // to validate a phone number
        $pattern =
        '/.{8,}/';

        $message = 'at least 8 characters';
        $this->pattern($name, $value, $pattern, $message, $required);
      }
    
    // Validate a field with a generic pattern
    public function pattern($name, $value, $pattern,
             $message, $required = true) {
        // Get Field object
        $field = $this->fields->getField($name);

        // If not required and empty, clear errors
        if (!$required && empty($value)) {
            $field->clearErrorMessage();
            return;
        }
        // Check field and set or clear error message
        $match = preg_match($pattern, $value);
        if ($match === false) {
            $field->setErrorMessage('Error testing field.');
        } else if ( $match != 1 ) {
            $field->setErrorMessage($message);
        } else {
            $field->clearErrorMessage();
        }
    }
    
     public function zip($name, $value, $required = false) {
        $field = $this->fields->getField($name);

        // Call the text method
        // and exit if it yields an error
        $this->text($name, $value, $required);
        if ($field->hasError()) { return; }

        // Call the pattern method
        // to validate a zip code
        $pattern =
        '/^(\d{5}(\d{4})?)?$/';

        $message = 'Invalid zip code.';
        $this->pattern($name, $value, $pattern, $message, $required);
      }

public function email($name, $value, $required = true) {

        $field = $this->fields->getField($name);

        // If not required and empty, clear errors
        if (!$required && empty($value)) {
            $field->clearErrorMessage();
            return;
        }

        // Call the text method
        // and exit if it yields an error
        $this->text($name, $value, $required);
        if ($field->hasError()) { return; }
// Split email address on @ sign and check parts
        $parts = explode('@', $value);
        if (count($parts) < 2) {
            $field->setErrorMessage('At sign required.');
            return;
        }
        if (count($parts) > 2) {
            $field->setErrorMessage(
                'Only one at sign allowed.');
            return;
        }
        $local = $parts[0];
        $domain = $parts[1];

        // Check lengths of local and domain parts
        if (strlen($local) > 64) {
            $field->setErrorMessage('Username too long.');
            return;
        }
        if (strlen($domain) > 255) {
            $field->setErrorMessage(
                'Domain name part too long.');
            return;
        }
// Patterns for address formatted local part
        $atom = '[[:alnum:]_!#$%&\'*+\/=?^`{|}~-]+';
        $dotatom = '(\.' . $atom . ')*';
        $address = '(^' . $atom . $dotatom . '$)';

        // Patterns for quoted text formatted local part
        $char = '([^\\\\"])';
        $esc  = '(\\\\[\\\\"])';
        $text = '(' . $char . '|' . $esc . ')+';
        $quoted = '(^"' . $text . '"$)';

        // Combined pattern for testing local part
        $localPattern =
            '/' . $address . '|' . $quoted . '/';

        // Call the pattern method and exit if error
        $this->pattern($name, $local, $localPattern,
                       'Invalid username part.');
        if ($field->hasError()) { return; }
        // Patterns for domain part
        $hostname =
            '([[:alnum:]]([-[:alnum:]]{0,62}[[:alnum:]])?)';
        $hostnames =
            '(' . $hostname . '(\.' . $hostname . ')*)';
        $top = '\.[[:alnum:]]{2,6}';
        $domainPattern = '/^' . $hostnames . $top . '$/';

        // Call the pattern method
        $this->pattern($name, $domain, $domainPattern,
                'Invalid domain name part.');
    }
    
    public function birthdate($name, $value,$required = false) {
        $field = $this->fields->getField($name);

        // Call the text method
        // and exit if it yields an error
        $this->text($name, $value, $required);
        if ($field->hasError()) { return; }

        // Call the pattern method
        // to validate a birthdate

        $date_pattern = '/^(0?[1-9]|1[0-2])\/'
             . '(0?[1-9]|[12][[:digit:]]|3[01])\/'
             . '[[:digit:]]{4}$/';
        $match = preg_match($date_pattern, $value);
        // Returns 0
        $message = 'Invalid date format.';
        $this->pattern($name, $value, $date_pattern, $message, $required);
      }
    
    
    public function state($name, $value, $required = false) {
        $field = $this->fields->getField($name);

        // Call the text method
        // and exit if it yields an error
        $this->text($name, $value, $required);
        if ($field->hasError()) { return; }

        // Call the pattern method
        // to validate a state pattern
        $pattern =
        '/^[a-z]{2}$/i';

        $message = '2 characters required.';
        $this->pattern($name, $value, $pattern, $message, $required);
      }
    
  public function expDate($name, $value,$required = false) {
        $field = $this->fields->getField($name);

        // Call the text method
        // and exit if it yields an error
        $this->text($name, $value, $required);
        if ($field->hasError()) { return; }

        // Call the pattern method
        // to validate a expDate

        $date_pattern = '/^(0?[1-9]|1[0-2])\/'
             . '[[:digit:]]{4}$/';
        $match = preg_match($date_pattern, $value);
        // Returns 0
        $message = 'Invalid expiration date mm/yyyy required';
        $this->pattern($name, $value, $date_pattern, $message, $required);
      }
    
    public function verify($name, $value, $value2, $required = true) {
        $field = $this->fields->getField($name);

        // Call the text method
        // and exit if it yields an error
        $this->text($name, $value, $required);
        if ($field->hasError()) { return; }

        if ($value == $value2) {
            $field->clearErrorMessage();
        } else {
            
            $field->setErrorMessage('Password do not match.');
        }
      }
    
    public function cardType($name, $value, $required = true) {
        $field = $this->fields->getField($name);
       
        // Call the text method
        // and exit if it yields an error
        $this->text($name, $value, $required);
        if ($field->hasError()) { return; }

      }
      
 
 public function cardNumber($name, $value, $cardType, $required = false)
    {
        $field = $this->fields->getField($name);

        // Call the text method
        // and exit if it yields an error
        $this->text($name, $value, $required);
        if ($field->hasError()) { return; }

        // Call the pattern method
        // to validate a card number
        //$cardpattern = '/^\d+-\d+-\d+-\d+$/';
        $cardpattern = '/^[0-9]+(-[0-9]+)+$/';
        $match = preg_match($cardpattern, $value);
        // Returns 0
        if ($match === false) {
            $field->setErrorMessage('Only digit or dashes');
        } else {
            $field->clearErrorMessage();
        }
        //$message = 'Only digits or dashes.';
        //$this->pattern($name, $value, $cardpattern, $message, $required);
    }
   
}
?>