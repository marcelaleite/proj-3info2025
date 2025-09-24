<?php
class Albinismo
{
    private ?int $id_albinismo;
    private int $usuario_id_usuario;
    private ?int $id_pai;
    private ?int $id_mae;
    private int $possui_albinismo; // 0 = não, 1 = sim

    public function __construct(
        ?int $id_albinismo,
        int $usuario_id_usuario,
        ?int $id_pai,
        ?int $id_mae,
        int $possui_albinismo
    ) {
        $this->id_albinismo = $id_albinismo;
        $this->usuario_id_usuario = $usuario_id_usuario;
        $this->id_pai = $id_pai;
        $this->id_mae = $id_mae;
        $this->possui_albinismo = $possui_albinismo;
    }

    // Getters
    public function getIdAlbinismo(): ?int { return $this->id_albinismo; }
    public function getUsuarioId(): int { return $this->usuario_id_usuario; }
    public function getIdPai(): ?int { return $this->id_pai; }
    public function getIdMae(): ?int { return $this->id_mae; }
    public function getPossuiAlbinismo(): int { return $this->possui_albinismo; }

    // Setters
    public function setIdAlbinismo(?int $v): void { $this->id_albinismo = $v; }
    public function setUsuarioId(int $v): void { $this->usuario_id_usuario = $v; }
    public function setIdPai(?int $v): void { $this->id_pai = $v; }
    public function setIdMae(?int $v): void { $this->id_mae = $v; }
    public function setPossuiAlbinismo(int $v): void { $this->possui_albinismo = $v; }
}
