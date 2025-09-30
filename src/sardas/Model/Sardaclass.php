<?php
class Sarda {
    private ?int $idPerfil;
    private int $usuarioId;
    private ?int $idPai;
    private ?int $idMae;
    private string $sarda;

    public function __construct(?int $idPerfil, int $usuarioId, ?int $idPai, ?int $idMae, string $sarda) {
        $this->idPai = $idPai;
        $this->idMae = $idMae;
    }

    // Getters
    public function getIdPerfil(): ?int { return $this->idPerfil; }
    public function getUsuarioId(): int { return $this->usuarioId; }
    public function getIdPai(): ?int { return $this->idPai; }
    public function getIdMae(): ?int { return $this->idMae; }
    public function getSarda(): string { return $this->sarda; }

    // Setters
    public function setSarda(string $sarda): void { $this->sarda = $sarda; }
}
