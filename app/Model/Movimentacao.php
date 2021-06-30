<?php
class Movimentacao extends AppModel
{
	public $useTable = 'movimentacoes';
	public $order = array( 'Movimentacao.created' => 'DESC' );
        
        public $belongsTo = array('ProdutoLoja','User');

	public function movimentar($pl_id, $quantidade, $acao)
	{
            if(!isset($pl_id) || empty($pl_id)) return false;
            $this->id=null;
            $data = array(
                    'produto_loja_id' => $pl_id, 
                    'acao' => $acao, 
                    'user_id' => $_SESSION['Auth']['User']['id'], 
                    'quantidade' => $quantidade,
            );
            //pr($data);
            if ($this->save($data))
                return true;
            //echo "POT";
            return false;
	}
        
}
?>
