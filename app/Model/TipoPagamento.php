<?php
class TipoPagamento extends AppModel
{
	public $useTable = 'tipos_pagamentos';
	public $displayField = 'nome';
	public $order = 'nome';
}