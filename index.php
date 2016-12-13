<?php

require_once 'src/utils.php';

echo Utils::sanitize("Fulãno"); // Fulano

echo Utils::is_cnpj("61.190.658/0001-06"); // true`

echo Utils::is_cpf("440.400.000-99"); // true
