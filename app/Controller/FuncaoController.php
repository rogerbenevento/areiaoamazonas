<?php
class FuncaoController extends AppController
{
	public $model = 'Funcao';
        public $filter_with_like = Array( 'Funcao.nome');
        
        //
        //ADMIN
        //
        public function admin_index()
        {		
                parent::index();
                // Remove o inicio da action para o Paginator funcionar adequadamente 
                $this->params['action']=  substr($this->params['action'], strpos($this->params['action'], '_')+1);
        }
	
        public function admin_edit( $id = null ) 
        {
                parent::edit( $id );
        }

        public function admin_del( $id )
	{
                parent::del( $id );
        }
        
        public function admin_add() 
        {
                $this->setAction('admin_edit' );
        }
        //
        //RH
        //
        public function rh_index() 
        {
                $this->setAction('admin_index' );
        }
        public function rh_edit($id=null) 
        {
                $this->setAction('admin_edit',$id );
        }
        public function rh_del($id) 
        {
                $this->setAction('admin_del',$id );
        }
        public function rh_add() 
        {
                $this->setAction('rh_edit');
        }
}
?>
