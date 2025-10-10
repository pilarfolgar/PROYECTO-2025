<?php
class CI_Uruguay
{
    private function soloDigitos(string $s): string {
        return preg_replace('/\D+/', '', $s);
    }

    private function validarLargoEstricto(string $s, int $len): bool {
        return strlen($s) === $len && ctype_digit($s);
    }

    public function calcularDigito(string $numeros7): ?int {
        $nums = $this->soloDigitos($numeros7);
        if (!$this->validarLargoEstricto($nums, 7)) return null;
        $pesos = [2,9,8,7,6,3,4];
        $suma = 0;
        for ($i=0;$i<7;$i++) $suma += (int)$nums[$i]*$pesos[$i];
        $mod = $suma%10;
        return $mod === 0 ? 0 : 10-$mod;
    }

    public function validarCI(string $ci): bool {
        $digitos = $this->soloDigitos($ci);
        if (!$this->validarLargoEstricto($digitos, 8)) return false;
        $primeros7 = substr($digitos,0,7);
        $ultimo = (int)substr($digitos,7,1);
        $esperado = $this->calcularDigito($primeros7);
        return $esperado === $ultimo;
    }
}
?>
