<?php
class CidadesController extends AppController
{
	public $model = 'Cidade';
	public $layout = '';
	
	public function by_estado()
	{
		$this->conditions = array( 'Cidade.estado_id' => $this->params->query['estado_id'] );
		$this->set( 'cidades', $this->Cidade->find( 'list', array( 'conditions' => $this->conditions ) ) );
	}

    public function by_estado_cidade()
    {   
        //Configure::write('debug', 2);
       // $this->autoRender = false;
        $estado =  $this->params->query['estado_id'];
        $cidade =  strtoupper(CidadeDB($this->params->query['cidade']));
        $this->conditions = array( 'Cidade.estado_id' => $estado, 'Cidade.nome LIKE' => "{$cidade}");
        $list = $this->Cidade->find( 'list', array( 'conditions' => $this->conditions ));
        
        $this->set( 'cidades', $list);
    }
        //
        // ADMIN
        //
        public function admin_by_estado(){
            $this->setAction('by_estado');
        }

        public function admin_by_estado_cidade(){
            $this->setAction('by_estado_cidade');
        }
        //
        // LOGISTICA
        //
        public function logistica_by_estado(){
            $this->setAction('by_estado');
        }
        public function logistica_by_estado_cidade(){
            $this->setAction('by_estado_cidade');
        }

        //
        //GERENTE
        //
        public function gerente_by_estado(){
            $this->setAction('by_estado');
        }
        public function gerente_by_estado_cidade(){
            $this->setAction('by_estado_cidade');
        }

        //
        //VENDEDOR
        //
        public function vendedor_by_estado(){
            $this->setAction('by_estado');
        }
        public function vendedor_by_estado_cidade(){
            $this->setAction('by_estado_cidade');
        }

        //
        //FINANCEIRO
        //
        public function financeiro_by_estado(){
            $this->setAction('by_estado');
        }
        public function financeiro_by_estado_cidade(){
            $this->setAction('by_estado_cidade');
        }

        //
        //RH
        //
        public function rh_by_estado(){
            $this->setAction('by_estado');
        }
        public function rh_by_estado_cidade(){
            $this->setAction('by_estado_cidade');
        }

}