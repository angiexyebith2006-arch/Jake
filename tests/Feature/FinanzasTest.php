<?php

namespace Tests\Feature;

use Tests\TestCase;

class FinanzasTest extends TestCase
{
    public function test_error_monto_negativo()
    {
        $response = $this->post('/finanzas', [
            'id_categoria' => 1,
            'monto' => -500,
            'fecha' => '2025-05-10',
            'descripcion' => 'Prueba error'
        ]);

        // VALIDAR QUE EXISTAN ERRORES
        $response->assertSessionHasErrors('monto');
    }
}