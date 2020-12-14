<?php

namespace App\Utils;

use App\Models\Enums\Roles;

use Illuminate\Database\Capsule\Manager as DB;

class Helper
{
	public static function defaultPageSize()
	{
		return 50;
	}
	public static function CallRaw($procName, $parameters = null, $isExecute = false)
	{
		$syntax = '';
		if (isset($parameters)) {
			for ($i = 0; $i < count($parameters); $i++) {
				$syntax .= (!empty($syntax) ? ',' : '') . '?';
			}
		}
		if ($_REQUEST['dbb'] == 'mySql') {
			$syntax = 'CALL ' . $procName . '(' . $syntax . ');';
		} else {
			$syntax = 'execute ' . $procName . ' ' . $syntax . ';';
		}

		$pdo = DB::connection()->getPdo();
		$pdo->setAttribute(\PDO::ATTR_DEFAULT_FETCH_MODE, true);
		$stmt = $pdo->prepare($syntax, [\PDO::ATTR_CURSOR => \PDO::CURSOR_SCROLL]);
		if (isset($parameters)) {
			for ($i = 0; $i < count($parameters); $i++) {
				$stmt->bindValue((1 + $i), $parameters[$i]);
			}
		}

		$exec = $stmt->execute();
		if (!$exec) return $pdo->errorInfo();
		if ($isExecute) return $exec;

		$results = [];
		do {
			try {
				$results[] = $stmt->fetchAll(\PDO::FETCH_OBJ);
			} catch (\Exception $ex) {
				$a = 'aa';
			}
		} while ($stmt->nextRowset());
		// $results = [];
		// // $stmt = $pdo->query($syntax);
		// do {
		//     // $rows = $stmt->fetchAll(\PDO::FETCH_NUM); // Keys will be start from zero , one, two
		//     $rows = $stmt->fetchAll(\PDO::FETCH_BOTH); // Keys will be start from zero , one, two
		//     // $rows = $stmt->fetchAll(\PDO::FETCH_ASSOC); // Column names will be assigned for each value

		//     if ($rows) {
		//         $results[] = $rows;
		//     }
		// } while ($stmt->nextRowset());

		if (1 === count($results)) return $results[0];
		return $results;
	}

	public static function createWhere($singleCriteriaObj, $query)
	{
		if (strcasecmp($singleCriteriaObj["condition"], 'or') == 0) {
			if (strcasecmp($singleCriteriaObj["operator"], 'whereBetween') == 0) {
				return $query->orWhereBetween($singleCriteriaObj["field"], $singleCriteriaObj["value"]);
			} else if (strcasecmp($singleCriteriaObj["operator"], 'whereNotBetween') == 0) {
				return $query->orWhereNotBetween($singleCriteriaObj["field"], $singleCriteriaObj["value"]);
			} else {
				return $query->orWhere(
					$singleCriteriaObj["field"],
					$singleCriteriaObj["operator"],
					$singleCriteriaObj["value"]
				);
			}
		} else {
			if (strcasecmp($singleCriteriaObj["operator"], 'whereBetween') == 0) {
				return $query->whereBetween($singleCriteriaObj["field"], $singleCriteriaObj["value"]);
			} else if (strcasecmp($singleCriteriaObj["operator"], 'whereNotBetween') == 0) {
				return $query->whereNotBetween($singleCriteriaObj["field"], $singleCriteriaObj["value"]);
			} else {
				return $query->where(
					$singleCriteriaObj["field"],
					$singleCriteriaObj["operator"],
					$singleCriteriaObj["value"]
				);
			}
		}
	}

	public static function error($msg, $status, $response)
	{
		return $response->withStatus($status)
			->withHeader('Content-Type', 'text/html')
			->write($msg);
	}
	public function array_columns(array $arr, array $keysSelect)
	{
		$keys = array_flip($keysSelect);
		$filteredArray = array_map(function ($a) use ($keys) {
			return array_intersect_key($a, $keys);
		}, $arr);

		return $filteredArray;
	}
	public function checkIfAdminOrReturnCustomerId()
	{
		$user = $_REQUEST['JWT_Decoded_User'];
		if (isset($user)) {
			if (isset($user['roles'])) {
				foreach ($user['roles'] as $role) {
					if (strcmp($role['name'], 'Admin') == 0 && $role['system_level'] == 1) {
						return false;
					}
				}

				unset($role);
			}
		}
		if (isset($user['customer_id'])) {
			return $user['customer_id'];
		} else {
			return -1;
		}
	}
	public function getMainRole($roles)
	{
		if (isset($roles)) {
			//check if admin
			foreach ($roles as $role) {
				if (strcmp($role['name'], 'Admin') == 0 && $role['system_level'] == 1) {
					return Roles::admin;
				}
			}
			unset($role);

			//if Sub Contractor Admin
			foreach ($roles as $role) {
				if (strcmp($role['name'], 'Back Office') == 0 && $role['system_level'] == 1) {
					return Roles::back_office;
				}
			}
			unset($role);
			//if Sub Contractor User
			foreach ($roles as $role) {
				if (strcmp($role['name'], 'Read Only') == 0 && $role['system_level'] == 1) {
					return Roles::read_only;
				}
			}
			unset($role);
			//if Technician
			foreach ($roles as $role) {
				if (strcmp($role['name'], 'Technician') == 0 && $role['system_level'] == 1) {
					return Roles::technician;
				}
			}
			unset($role);
		}

		//if no role, he'll be considered customer user
		return Roles::back_office;
	}
	public function dummyabc($var1, $var2, $var3)
	{
		if ($var1 == $var2) {
		}
		return null;
	}
}
