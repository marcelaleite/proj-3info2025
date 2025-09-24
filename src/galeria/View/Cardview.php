<?php
class CardView {
    private $titulo;
    private $descricao;
    private $imagem;
    private $link;

    public function __construct($titulo, $descricao, $imagem, $link) {
        $this->titulo = $titulo;
        $this->descricao = $descricao;
        $this->imagem = $imagem;
        $this->link = $link;
    }

    public function render($tipoCard = "card1") {
        return "
            <div class='card {$tipoCard}'>
                <h2>{$this->titulo}</h2>
                <img src='{$this->imagem}' alt='{$this->titulo}' class='prof'>
                <button><a href='{$this->link}' target='_blank'>SOBRE</a></button>
            </div>
        ";
    }
}
?>
