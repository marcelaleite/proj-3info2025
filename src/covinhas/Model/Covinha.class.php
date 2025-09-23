<?php
class Covinha
{
    private ?int $id_perfil;
    private int $usuario_id_usuario;
    private ?int $id_pai;
    private ?int $id_mae;
    private string $cov_queixo;
    private string $cov_bochecha;

    public function __construct(
        ?int $id_perfil,
        int $usuario_id_usuario,
        ?int $id_pai,
        ?int $id_mae,
        string $cov_queixo,
        string $cov_bochecha
    ) {
        $this->id_perfil = $id_perfil;
        $this->usuario_id_usuario = $usuario_id_usuario;
        $this->id_pai = $id_pai;
        $this->id_mae = $id_mae;
        $this->cov_queixo = $cov_queixo;
        $this->cov_bochecha = $cov_bochecha;
    }

    public function getIdPerfil(): ?int { return $this->id_perfil; }
    public function getUsuarioId(): int { return $this->usuario_id_usuario; }
    public function getIdPai(): ?int { return $this->id_pai; }
    public function getIdMae(): ?int { return $this->id_mae; }
    public function getCovQueixo(): string { return $this->cov_queixo; }
    public function getCovBochecha(): string { return $this->cov_bochecha; }

    public function setIdPerfil(?int $v): void { $this->id_perfil = $v; }
    public function setUsuarioId(int $v): void { $this->usuario_id_usuario = $v; }
    public function setIdPai(?int $v): void { $this->id_pai = $v; }
    public function setIdMae(?int $v): void { $this->id_mae = $v; }
    public function setCovQueixo(string $v): void { $this->cov_queixo = $v; }
    public function setCovBochecha(string $v): void { $this->cov_bochecha = $v; }
}
