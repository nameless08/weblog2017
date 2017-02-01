<?php 


function sanitizeAddError($attribute, $message, array &$errors) {

	$errors[$attribute] = strtr($message, [

		'{attribute}' => $attribute,

	]);

}

function sanitize(array $data, array $rules, array &$errors = null){

	$errors = $errors ?? [];

	foreach ($rules as $key => $rule){

		$rule['flag'] = ($rule['flags'] ?? 0) | FILTER_NULL_ON_FAILURE;
		$rule['required'] = (bool) ($rules['required'] ?? false);
		$rule['message'] = $rule['message'] ?? '';
		$rules[$key] = $rule;

	}

	$data = array_map('trim', $data);
	$filteredData = filter_var_array($data, $rules); 

	foreach($filteredData as $attribute => $value) {

		$rule = $rules[$attribute];

		if(is_null($value)) {

			if(
				isset($data[$attribute]) || 
				$data[$attribute] ||
				($data[$attribute] === '' && $rule['required'])
			) {

				sanitizeAddError(

					$attribute,
					$rule['message'] ?:'Не корректно введено "{attribute}".',
					$errors
				);

			}

		}

		if(is_string($value)) {

			$value = trim($value);
			$filteredData[$attribute] = $value;

			if(!$value && $rule['required']) {
				// ? ?????????????? "{attribute}"
			}

		}


	}

	return $filteredData;

}




