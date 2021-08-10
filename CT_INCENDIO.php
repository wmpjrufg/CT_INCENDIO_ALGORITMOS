<?php

#   /$$$$$$  /$$$$$$$$       /$$$$$$ /$$   /$$  /$$$$$$  /$$$$$$$$ /$$   /$$ /$$$$$$$  /$$$$$$  /$$$$$$                                                               
#  /$$__  $$|__  $$__/      |_  $$_/| $$$ | $$ /$$__  $$| $$_____/| $$$ | $$| $$__  $$|_  $$_/ /$$__  $$                                                              
# | $$  \__/   | $$           | $$  | $$$$| $$| $$  \__/| $$      | $$$$| $$| $$  \ $$  | $$  | $$  \ $$                                                              
# | $$         | $$           | $$  | $$ $$ $$| $$      | $$$$$   | $$ $$ $$| $$  | $$  | $$  | $$  | $$                                                              
# | $$         | $$           | $$  | $$  $$$$| $$      | $$__/   | $$  $$$$| $$  | $$  | $$  | $$  | $$                                                              
# | $$    $$   | $$           | $$  | $$\  $$$| $$    $$| $$      | $$\  $$$| $$  | $$  | $$  | $$  | $$                                                              
# |  $$$$$$/   | $$          /$$$$$$| $$ \  $$|  $$$$$$/| $$$$$$$$| $$ \  $$| $$$$$$$/ /$$$$$$|  $$$$$$/                                                              
#  \______/    |__/         |______/|__/  \__/ \______/ |________/|__/  \__/|_______/ |______/ \______/    

######################################################################
# UNIVERSIDADE FEDERAL DE CATALÃO (UFCAT)
# WANDERLEI MALAQUIAS PEREIRA JUNIOR,        ENG. CIVIL / PROF (UFCAT)
# ANA LARISSA DAL PIVA ARGENTA,              ENG. CIVIL / PROF (UFCAT)
# LUANNA LOPES LOBATO,                            COMP. / PROF (UFCAT)
# THIAGO JABUR BITTAR                             COMP. / PROF (UFCAT)
# JOSÉ ALFREDO CÔRTE VIEIRA                         ENG. CIVIL (UFCAT)
# RODRIGO FREITAS SILVA                             ENG. CIVIL (UFCAT)
# WALTER ALBERGARIA JUNIOR,                        SIST. INFO. (UFCAT)
######################################################################

######################################################################
# DESCRIÇÃO ALGORITMO:
# BIBLIOTECA PARA CÁLCULO DE VIGAS SOB SITUAÇÃO DE INCÊNDIO DESENVOL-
# VIDA PELO GRUPO DE PESQUISAS E ESTUDOS EM ENGENHARIA (GPEE)
######################################################################

# VERIFICAÇÃO DA ALÍNEA C NBR 14.432
function VALIDACAO_ENTRADA_14432_ALINEA_C($A_TOTAL, $N_PAVTOS, $Q_I, $TIPO_ESTRUTURA,  $CLASS_EDIFICA, $H, $A_L)
{
  /*
  Esta função avalia a necessidade das edificações gerais serem verificadas em situação de incêndio alínea C da NBR 14.432.

  Entrada:
  A_TOTAL           | Área total da edificação                          | m²   | Float
  N_PAVTOS          | Número de pavimentos da edificação                |      | Integer
  Q_I               | Carga de incêndio                                 | MJ/m²| Float
  TIPO_ESTRUTURA    | Tipo da estrutura                                 |      | String
  CLASS_EDIFICA     | Classe da edificação Tabela B.1 NBR 14.432        |      | String
  H                 | Altura da edificação ou profundida do subsolo     | m    | Float
  A_L               | Edificação aberta lateralmente                    |      | Boolean

  Saída:
  CALC_CARD2        | Condição de validação da verificação de incêndio  |      | Boolean
                    |    0 - Não é necessária a verificação             |      |
                    |    1 - É necessária a verificação                 |      |
  CALC_CARD2VALOR   | Condição para validação                           |      | String
  */
  # Valor inicial das variáveis de saída
  $CALC_CARD2 = 1;
  $CALC_CARD2VALOR = "Anexo A: NBR 14432 Alínea C: Deve-se verificar";
  # NBR 14432 alínea C.1 anexo A - Área total?
  if ($A_TOTAL <= 750):
      $CALC_CARD2 = 0;
      $CALC_CARD2VALOR = "Anexo A: NBR 14432 Alínea C.1 - Não se verifica";
  # NBR 14432 alínea C.2 anexo A - Área total?, Carga de incêndio? e Número de pavimentos?
  elseif ($N_PAVTOS <= 2 && $A_TOTAL <= 1500 && $Q_I <= 1000):
      $CALC_CARD2 = 0;  
      $CALC_CARD2VALOR = "Anexo A: NBR 14432 Alínea C.2 - Não se verifica";
  # NBR 14432 alínea C.3 anexo A - Pertence a estrutura e classes e altura?
  elseif ($TIPO_ESTRUTURA == "SISTEMA PRINCIPAL" && ($CLASS_EDIFICA == "F-3" || $CLASS_EDIFICA == "F-4" || $CLASS_EDIFICA == "F-7") && $H <= 23):
      $CALC_CARD2 = 0;  
      $CALC_CARD2VALOR = "Anexo A: NBR 14432 Alínea C.3 - Não se verifica";
  # NBR 14432 alínea C.4 anexo A - Pertence a estrutura e classes e altura?
  elseif ($TIPO_ESTRUTURA == "SISTEMA PRINCIPAL" && ($CLASS_EDIFICA == "G-1" || $CLASS_EDIFICA == "G-2") && $A_L == 1 && $H <= 30):
      $CALC_CARD2 = 0;  
      $CALC_CARD2VALOR = "Anexo A: NBR 14432 Alínea C.4 - Não se verifica";
  # NBR 14432 alínea C.5 anexo A - Pertence a estrutura e classes e altura?
  elseif ($TIPO_ESTRUTURA == "SISTEMA PRINCIPAL" && $CLASS_EDIFICA == "J-1" && $H <= 30):
      $CALC_CARD2 = 0;  
      $CALC_CARD2VALOR = "Anexo A: NBR 14432 Alínea C.5 - Não se verifica";
  endif;
  return array($CALC_CARD2, $CALC_CARD2VALOR);
}

# VERIFICAÇÃO DA ALÍNEA D NBR 14.432
function VALIDACAO_ENTRADA_14432_ALINEA_D($COB_FUNCAO, $EST_COMPART, $Q_I, $CLASS_EDIFICA, $C_A, $A_TOTAL, $F_A)
{
  /*
  Esta função avalia a necessidade das edificações térreas serem verificadas em situação de incêndio alínea D da NBR 14.432.

  Entrada:
  COB_FUNCAO        | Cobertura com função estrutural de piso           |      | Boolean
  EST_COMPART       | A estrutura é essencial à estabilidade de um      |      | Boolean
                    | elemento de compartimentação                      |      | 
  Q_I               | Carga de incêndio                                 | MJ/m²| Float
  CLASS_EDIFICA     | Classe da edificação Tabela B.1 NBR 14.432        |      | String
  C_A               | Chuveiro automático                               |      | Boolean
  A_TOTAL           | Área total da edificação                          | m²   | Float
  F_A               | 2 Fachadas de aproximação respeitando perímetro   |      | Boolean

  Saída:
  CALC_CARD2        | Condição de validação da verificação de incêndio  |      | Boolean
                    |    0 - Não é necessária a verificação             |      |
                    |    1 - É necessária a verificação                 |      |
  CALC_CARD2VALOR   | Condição para validação                           |      | String
  */
  # Valor inicial das variáveis de saída
  $CALC_CARD2 = 0;
  $CALC_CARD2VALOR = "Anexo A: NBR 14432 a Edificação térrea não deve-se verificar a peça ao incêndio conforme alínea D";
  # NBR 14432 alínea D.1 anexo A - Cobertura com função de piso ?
  if ($COB_FUNCAO == 1):
      $CALC_CARD2 = 1;  
      $CALC_CARD2VALOR = "Anexo A: NBR 14432 Alínea D.1 - Deve-se verificar";
  # NBR 14432 alínea D.2 anexo A - A estrutura verificada é essencial a estrutura de compartimentação?
  elseif ($EST_COMPART == 1):
      $CALC_CARD2 = 1;  
      $CALC_CARD2VALOR = "Anexo A: NBR 14432 Alínea D.2 - Deve-se verificar";
  # NBR 14432 alínea D.3 anexo A - A estrutura não tem uso industrial (excluem depositos) com Q_I > 500?
  elseif ($Q_I > 500 && ($CLASS_EDIFICA != "I-1" && $CLASS_EDIFICA != "I-2" && $CLASS_EDIFICA != "J-1" && $CLASS_EDIFICA != "J-2")):
      $CALC_CARD2 = 1;  
      $CALC_CARD2VALOR = "Anexo A: NBR 14432 Alínea D.3 - Deve-se verificar";
      # Condição de invalidação por chuveiro automático alíne E NBR 14432
      if (($C_A == 1) || ($F_A == 1 && $A_TOTAL <= 5000)):
          $CALC_CARD2 = 0;  
          $CALC_CARD2VALOR = "Anexo A: NBR 14432 a Edificação térrea não deve-se verificar a peça ao incêndio conforme alínea E.1 ou E.2";
      endif;
  # NBR 14432 alínea D.4 anexo A - A estrutura tem uso industrial com Q_I > 1200?
  elseif ($Q_I > 1200 && ($CLASS_EDIFICA == "I-1" || $CLASS_EDIFICA == "I-2")):
      $CALC_CARD2 = 1;  
      $CALC_CARD2VALOR = "Anexo A: NBR 14432 Alínea D.4 - Deve-se verificar";
      # Condição de invalidação por chuveiro automático alíne E NBR 14432
      if (($C_A == 1) || ($F_A == 1 && $A_TOTAL <= 5000)):
          $CALC_CARD2 = 0;  
          $CALC_CARD2VALOR = "Anexo A: NBR 14432 a Edificação térrea não deve-se verificar a peça ao incêndio conforme alínea E.1 ou E.2";
      endif;
  # NBR 14432 alínea D.5 anexo A - A estrutura tem uso de depósito com Q_I > 2000?
  elseif ($Q_I > 2000 && ($CLASS_EDIFICA == "J-1" || $CLASS_EDIFICA == "J-2")):
      $CALC_CARD2 = 1;
      $CALC_CARD2VALOR = "Anexo A: NBR 14432 Alínea D.5 - Deve-se verificar";
      # Condição de invalidação por chuveiro automático alíne E NBR 14432
      if (($C_A == 1) || ($F_A == 1 && $A_TOTAL <= 5000)):
          $CALC_CARD2 = 0;  
          $CALC_CARD2VALOR = "Anexo A: NBR 14432 a Edificação térrea não deve-se verificar a peça ao incêndio conforme alínea E.1 ou E.2";
      endif;
  endif;
  return array($CALC_CARD2, $CALC_CARD2VALOR);
}

# DETERMINA SE A PEÇA ESTRUTURAL ANALISADA DEVE SER VERIFICADA AO INCÊNDIO
function VERIFICACOES_INICIAIS_INCENDIO_0001($TIPO_ESTRUTURA, $PARTICIPA_ESTABILIDADE, $H, $ACESSO, $N_PAVTOS, $COB_FUNCAO, $EST_COMPART, $A_PAVTO, $A_TOTAL, $CLASS_EDIFICA, $A_L, $Q_I, $C_A, $F_A)
{
  /*
  Esta função verifica se a peça estrutural é isenta dos requisitos de resistência ao fogo segundo a NBR 14.432 (2001).

  Entrada:
  TIPO_ESTRUTURA          | Tipo da estrutura                                 |      | String
  PARTICIPA_ESTABILIDADE  | A peça participa da estabilidade estrutura        |      | Boolean 
  H                       | Altura da edificação ou profundida do subsolo     | m    | Float
  ACESSO                  | Acesso de pessoas com restrição a mobilidade      |      | Boolean
  N_PAVTOS                | Número de pavimentos da edificação                |      | Integer
  COB_FUNCAO              | Cobertura com função estrutural de piso           |      | Boolean
  EST_COMPART             | A estrutura é essencial à estabilidade de um      |      | Boolean
                          | elemento de compartimentação                      |      |
  A_PAVTO                 | Área total do pavimento                           | m²   | Float 
  A_TOTAL                 | Área total da edificação                          | m²   | Float
  CLASS_EDIFICA           | Classe da edificação Tabela B.1 NBR 14.432        |      | String
  A_L                     | Edificação aberta lateralmente                    |      | Boolean
  Q_I                     | Carga de incêndio                                 | MJ/m²| Float
  C_A                     | Chuveiro automático                               |      | Boolean
  F_A                     | 2 Fachadas de aproximação respeitando perímetro   |      | Boolean

  Saída:
  CALC_CARD               | Condição de validação da verificação de incêndio  |      | Boolean
                          |    0 - Não é necessária a verificação             |      |
                          |    1 - É necessária a verificação                 |      |
  CALC_VALOR              | Condição para validação                           |      | String
  */  
  # Verificação da necessida de verificação de incêndio
  # Edificações térreas
  if ($N_PAVTOS == 1):
      [$CALC, $CALC_VALOR] = VALIDACAO_ENTRADA_14432_ALINEA_D($N_PAVTOS, $COB_FUNCAO, $EST_COMPART, $Q_I, $CLASS_EDIFICA, $C_A, $A_TOTAL, $F_A);
  # Edificações com mais de 1 pavimento
  else:
      [$CALC, $CALC_VALOR] = VALIDACAO_ENTRADA_14432_ALINEA_C($A_TOTAL, $N_PAVTOS, $Q_I, $TIPO_ESTRUTURA,  $CLASS_EDIFICA, $H, $A_L);
  endif;
  # Impressões no console para versão de testes (Comentar este trecho quando implementar na versão online)
  echo "  \n";
  echo "------------------------------------------------\n";
  echo "CORETECTOOLS - CONCRETO INCÊNDIO                \n";
  echo "VERIFICAÇÃO DE VIGAS EM SITUAÇÃO DE INCÊNDIO    \n";
  echo "------------------------------------------------\n";
  echo "  \n";
  echo "-----------------------------------------------\n";
  echo "DADOS DE ENTRADA:\n";
  echo "-----------------------------------------------\n";
  if ($TIPO_ESTRUTURA == "SUBSOLO"):
      echo "Tipo da estrutura                                                           = Subsolo\n";
  else:
      echo "Tipo da estrutura                                                           = Sistema principal\n";
  endif;
    if ($PARTICIPA_ESTABILIDADE == 0):
      echo "Pertence ao sistema responsável pela estabilidade estrutural?               = Não\n";
  else:
      echo "Pertence ao sistema responsável pela estabilidade estrutural?               = Sim\n";
  endif;
  if ($TIPO_ESTRUTURA == 0):
      echo "Profundidade do subsolo (h_s)                                               = $H m\n";
  else:
      echo "Altura da edificação (h)                                                    = $H m\n";
  endif;
  if ($ACESSO == 0):
      echo "Acesso de pessoas com restrição de mobilidade?                              = Não\n";
  else:
      echo "Acesso de pessoas com restrição de mobilidade?                              = Sim\n";
  endif;
  echo "Total de pavimentos                                                             = $N_PAVTOS\n";
  if ($COB_FUNCAO == 0):
      echo "Cobertura tem função de piso?                                               = Não\n";
  elseif ($COB_FUNCAO == 1):
      echo "Cobertura tem função de piso?                                               = Sim\n";
  elseif ($COB_FUNCAO == -1989):
      echo "Critério de cobertura não se aplica (subdivisão 1 da alínea d do Anexo A, NBR 14432:2001)\n";
  endif;
  if ($EST_COMPART == 0):
      echo "A estrutura é essencial à estabilidade de um elemento de compartimentação?  = Não\n";
  elseif ($EST_COMPART == 1):
      echo "A estrutura é essencial à estabilidade de um elemento de compartimentação?  = Sim\n";
  elseif ($EST_COMPART == -1989):
      echo "Critério de compartimentação não se aplica (subdivisão 2 da alínea d do Anexo A, NBR 14432:2001)\n";
  endif;
  echo "Área do pavimento                                                               = $A_PAVTO m²\n";
  echo "Área total                                                                      = $A_TOTAL m²\n";
  echo "Classificação da edifição                                                       = $CLASS_EDIFICA\n";
  if ($A_L == 0):
      echo "Aberto lateralmente                                                         = Não\n";
  elseif ($A_L == 1):
      echo "Aberto lateralmente                                                         = Sim\n";
  elseif ($A_L == -1989):
      echo "Critério de abertura lateral não se aplica (4º item da alínea c do Anexo A, NBR 14432:2001)\n";
  endif;
  echo "Carga de incêndio específica                                                    = $Q_I MJ/m²\n";
  if ($C_A == 0):
      echo "Chuveiros automáticos                                                       = Não\n";
  else:
      echo "Chuveiros automáticos                                                       = Sim\n";
  endif;
  if ($F_A == 0):
      echo "Pelo menos 2 fachadas de aproximação                                        = Não\n";
  elseif ($F_A == 1):
      echo "Pelo menos 2 fachadas de aproximação                                        = Sim\n";
  elseif ($F_A == -1989):
      echo "Critério de fachada de aproximação não se aplica (2º item da alínea e do Anexo A, NBR 14432:2001)\n";
  endif;
  echo "Necessidade de verificação do sistema estrutural quanto aos requisitos de incêndio\n";
  if ($CALC == 0):
      echo "Não é necessário avaliar esta estrutura quanto aos critérios de incêndio\n";
      echo $CALC_VALOR;
  else:
      echo "É necessário avaliar esta estrutura quanto aos critérios de incêndio\n";
      echo $CALC_VALOR;
  endif;
  echo "\n-----------------------------------------------\n\n";
  return array($CALC, $CALC_VALOR);
}

# DETERMINA O TEMPO EQUIVALENTE T_E
function TEMPO_REQUERIDO_RESISTENCIA_FOGO($TRRF, $A_V, $H_I, $A_H, $B_I, $D_A, $GAMMA_S2, $A_PAVTO, $C_A, $Q_I)
{
  // IMPRESSÃO NO CONSOLE PARA TESTE
  echo "------------------------------------------------\n";
  echo "CORETECTOOLS - CONCRETO INCÊNDIO                       \n";
  echo "VERIFICAÇÃO DE VIGAS EM SITUAÇÃO DE INCÊNDIO       \n";
  echo "------------------------------------------------\n\n";
  echo "-----------------------------------------------\n";
  echo "PARÂMETROS DE ENTRADA:\n";
  echo "-----------------------------------------------\n";
  echo "TRRF                                             = $TRRF min\n";
  echo "Área de ventilação vertical                      = $A_V m²\n";
  echo "Altura do compartimento                          = $H_I m\n";
  echo "Altura do piso habitável mais elevado            = $A_H m\n";
  if ($B_I == 0):
      echo "Brigada de incêndio                              = Não\n";
  else:
      echo "Brigada de incêndio                              = Sim\n";
  endif;
  if ($D_A == 0):
      echo "Detecção automática                              = Não\n";
  else:
      echo "Detecção automática                              = Sim\n";
  endif;
  echo "Risco de ativação do incêndio                    = $GAMMA_S2\n";
  echo "-----------------------------------------------\n\n";
  # O QUE É ISSO
  if ($A_V/$A_PAVTO > 0.3):
      $FACTOR_AVAF = 0.3;
  elseif ($A_V/$A_PAVTO >= 0.025 && $A_V/$A_PAVTO <= 0.3):
      $FACTOR_AVAF = $A_V/$A_PAVTO;
  elseif ($A_V/$A_PAVTO < 0.025):
      $FACTOR_AVAF = 0.025;
  endif;
  # O QUE É ISSO
  $FACTOR_W = pow (6/$H_I , 0.3) * (0.62 + 90 * pow (0.4 - $FACTOR_AVAF , 4));
  if ($FACTOR_W >= 0.5):
      $W = $FACTOR_W;
  else:
      $W = 0.5;
  endif;
  # O QUE É ISSO
  if ($C_A == 0):
      $GAMMA_N1 = 1;
  else:
      $GAMMA_N1 = 0.6;
  endif; 
  # O QUE É ISSO
  if ($B_I == 0):
      $GAMMA_N2 = 1;
  else:
      $GAMMA_N2 = 0.9;
  endif;
  # O QUE É ISSO
  if ($D_A == 0):
      $GAMMA_N3 = 1;
  else:
      $GAMMA_N3 = 0.9;
  endif;
  # O QUE É ISSO
  $GAMMA_N = $GAMMA_N1 * $GAMMA_N2 * $GAMMA_N3;
  # O QUE É ISSO
  $FACTOR_GAMMA_S1 = 1 + ($A_PAVTO * ($A_H + 3)/pow (10,5));
  # O QUE É ISSO
  if ($FACTOR_GAMMA_S1 < 1):
      $GAMMA_S1 = 1;
  elseif ($FACTOR_GAMMA_S1 >= 1 && $FACTOR_GAMMA_S1 <= 3):
      $GAMMA_S1 = $FACTOR_GAMMA_S1;
  elseif ($FACTOR_GAMMA_S1 > 3):
      $GAMMA_S1 = 3;
  endif;
  # O QUE É ISSO
  $GAMMA_S = $GAMMA_S1 * $GAMMA_S2;

  $T_E = 0.07 * $Q_I * $W * $GAMMA_N * $GAMMA_S;

    
  if ($T_E < ($TRRF - 30)):
     $TRRF_FINAL = $TRRF - 30;
  elseif ($T_E > $TRRF):
     $TRRF_FINAL = $TRRF;
  elseif ($T_E >= ($TRRF - 30) && $T_E <= $TRRF):
     $TRRF_FINAL = $T_E;
  endif;

  if ($TRRF_FINAL < 15):
      $TRRF_FINAL = 15;
  endif;

    
  echo "-----------------------------------------------\n";
  echo "PROCESSAMENTO:\n";
  echo "Fator W que considera a influência da ventilação e da altura do compartimento                                       = $FACTOR_W  \n";
  echo "Fator GAMMA N1 relativo a existência de chuveiros automáticos                                                       = $GAMMA_N1 \n";
  echo "Fator GAMMA N2 relativo a existência de brigadas contra incêndio                                                    = $GAMMA_N2 \n";
  echo "Fator GAMMA N3 relativo a existência de detecção automática                                                         = $GAMMA_N3 \n";
  echo "Fator GAMMA N (GAMMA N1 x GAMMA N2 x GAMMA N3)                                                                      = $GAMMA_N \n";
  echo "Fator GAMMA S1 que considera a área do piso do compartimento e a altura do piso habitável mais elevado              = $GAMMA_S1 \n";
  echo "Fator GAMMA S2 relativo ao risco de ativação do incêndio                                                            = $GAMMA_S2 \n";
  echo "Fator GAMMA S (GAMMA S1 x GAMMA S2)                                                                                 = $GAMMA_S \n";
  echo "TE                                                                                                                  = $T_E min\n";
  echo "TRRF                                                                                                                = $TRRF min\n";  
  echo "TRRF adotado                                                                                                        = $TRRF_FINAL min\n";
  echo "-----------------------------------------------\n\n";

  return $TRRF_FINAL;
}

function INTERPOLACAO_LINEAR($X_1, $X_2, $X, $Y_1, $Y_2)
{
  #INTERPOLAÇÃO LINEAR
  $Y = $Y_1 + (($X - $X_1) / ($X_2 - $X_1) * ($Y_2 - $Y_1));
  return $Y;
}

function TABELA_TRRF($VETOR_TABELAX, $VETOR_TABELAY, $TRRF_FINAL)
{
  # VALOR
  if ($TRRF_FINAL == 15):
      $VALOR = $VETOR_TABELAY[0]; 
  elseif ($TRRF_FINAL > 15 && $TRRF_FINAL < 30):
      $VALOR = INTERPOLACAO_LINEAR($VETOR_TABELAX[0], $VETOR_TABELAX[1], $TRRF_FINAL, $VETOR_TABELAY[0], $VETOR_TABELAY[1]); 
  elseif ($TRRF_FINAL == 30):
      $VALOR = $VETOR_TABELAY[1]; 
  elseif ($TRRF_FINAL > 30 && $TRRF_FINAL < 60):
      $VALOR = INTERPOLACAO_LINEAR($VETOR_TABELAX[1], $VETOR_TABELAX[2], $TRRF_FINAL, $VETOR_TABELAY[1], $VETOR_TABELAY[2]);
  elseif ($TRRF_FINAL == 60):
      $VALOR = $VETOR_TABELAY[2];
  elseif ($TRRF_FINAL > 60 && $TRRF_FINAL < 90):
      $VALOR = INTERPOLACAO_LINEAR($VETOR_TABELAX[2], $VETOR_TABELAX[3], $TRRF_FINAL, $VETOR_TABELAY[2], $VETOR_TABELAY[3]);
  elseif ($TRRF_FINAL == 90):
      $VALOR = $VETOR_TABELAY[3];
  elseif ($TRRF_FINAL > 90 && $TRRF_FINAL < 120):
      $VALOR = INTERPOLACAO_LINEAR($VETOR_TABELAX[3], $VETOR_TABELAX[4], $TRRF_FINAL, $VETOR_TABELAY[3], $VETOR_TABELAY[4]);
  elseif ($TRRF_FINAL == 120):
      $VALOR = $VETOR_TABELAY[4];      
  elseif ($TRRF_FINAL > 120 && $TRRF_FINAL < 180):
      $VALOR = INTERPOLACAO_LINEAR($VETOR_TABELAX[4], $VETOR_TABELAX[5], $TRRF_FINAL, $VETOR_TABELAY[4], $VETOR_TABELAY[5]); 
  elseif ($TRRF_FINAL == 180):
      $VALOR = $VETOR_TABELAY[5];
  endif; 
  return $VALOR;
}

function MONTAGEM_TABELA_DIMENSOES_COMBINACOES($TRRF_FINAL)
{
  # COMBINAÇÃO 1 B_MIN e C1_MIN
  $TABELA_TRRF = [15, 30, 60, 90, 120, 180];
  $TABELA_BMIN1 = [40, 80, 120, 140, 190, 240];
  $TABELA_C1MIN1 = [18, 25, 40, 60, 68, 80];
  $B_MIN1 = TABELA_TRRF($TABELA_TRRF, $TABELA_BMIN1, $TRRF_FINAL);
  $C1_MIN1 = TABELA_TRRF($TABELA_TRRF, $TABELA_C1MIN1, $TRRF_FINAL);
  
  # COMBINAÇÃO 2 B_MIN e C1_MIN
  $TABELA_BMIN2 = [100, 120, 160, 190, 240, 300];
  $TABELA_C1MIN2 = [13, 20, 35, 45, 60, 70];
  $B_MIN2 = TABELA_TRRF($TABELA_TRRF, $TABELA_BMIN2, $TRRF_FINAL);
  $C1_MIN2 = TABELA_TRRF($TABELA_TRRF, $TABELA_C1MIN2, $TRRF_FINAL);  

 # COMBINAÇÃO 3 B_MIN e C1_MIN
  $TABELA_BMIN3 = [145, 160, 190, 300, 300, 400];
  $TABELA_C1MIN3 = [8, 15, 30, 40, 55, 65];
  $B_MIN3 = TABELA_TRRF($TABELA_TRRF, $TABELA_BMIN3, $TRRF_FINAL);
  $C1_MIN3 = TABELA_TRRF($TABELA_TRRF, $TABELA_C1MIN3, $TRRF_FINAL);
  
 # COMBINAÇÃO 4 B_MIN e C1_MIN
  $TABELA_BMIN4 = [135, 190, 300, 400, 500, 600];
  $TABELA_C1MIN4 = [10, 15, 25, 35, 50, 60];
  $B_MIN4 = TABELA_TRRF($TABELA_TRRF, $TABELA_BMIN4, $TRRF_FINAL);
  $C1_MIN4 = TABELA_TRRF($TABELA_TRRF, $TABELA_C1MIN4, $TRRF_FINAL); 
  
  echo "-----------------------------------------------\n";
  echo "PROCESSAMENTO DA TABELA:\n\n";
  echo "Tempo Requerido de Resistência ao Fogo = $TRRF_FINAL min\n\n";
  echo "                                  bmín    c1mín\n";
  echo "Combinação 1:                     $B_MIN1      $C1_MIN1\n";
  echo "Combinação 2:                     $B_MIN2      $C1_MIN2\n"; 
  echo "Combinação 3:                     $B_MIN3      $C1_MIN3\n";
  echo "Combinação 4:                     $B_MIN4      $C1_MIN4\n";
  echo "-----------------------------------------------\n";

  return array($B_MIN1, $C1_MIN1, $B_MIN2, $C1_MIN2, $B_MIN3, $C1_MIN3, $B_MIN4, $C1_MIN4);
}





function VERIFICAÇÃO_DA_VIGA_1_CAM($VIGA_ACO, $B_W, $B_MIN1, $C1_MIN1, $B_MIN2, $C1_MIN2,$B_MIN3, $C1_MIN3, $B_MIN4, $C1_MIN4, $N_BAR)
{
  echo "-----------------------------------------------\n";
  echo "PROCESSAMENTO DA VIGA:\n";
  echo "-----------------------------------------------\n";
  if ($B_W > $B_MIN2):
      echo "Atende o valor de b_min da Combinação 3 \n\n";
  else:
      echo "Não atende o valor de b_min da Combinação 3\n\n";
      echo "Especificar barras de canto com diâmetro imediatamente superior\n\n";
  endif;
  for ($I = 0; $I < $N_BAR; $I++):
      $VIGA_YCG[$I] = $VIGA_ACO[$I][4];
  endfor;
  $C1 = min($VIGA_YCG);
  [$CRITERIO_BW1, $CRITERIO_BW2, $CRITERIO_BW3, $CRITERIO_BW4, $CRITERIO_C1_1, $CRITERIO_C1_2, $CRITERIO_C1_3, $CRITERIO_C1_4] = COMPARA_TABELA_TRRF($C1, $B_W, $B_MIN1, $C1_MIN1, $B_MIN2, $C1_MIN2,$B_MIN3, $C1_MIN3, $B_MIN4, $C1_MIN4);
  echo "-----------------------------------------------\n";
  echo "TABELA DIMENSÕES MÍNIMAS:\n\n";
  echo "-----------------------------------------------\n";
  echo "                    Atende bmin   Atende c1 \n";
  echo "Combinação 1:           $CRITERIO_BW1         $CRITERIO_C1_1\n";
  echo "Combinação 2:           $CRITERIO_BW2         $CRITERIO_C1_2\n";
  echo "Combinação 3:           $CRITERIO_BW3         $CRITERIO_C1_3\n";
  echo "Combinação 4:           $CRITERIO_BW4         $CRITERIO_C1_4\n";
  echo "-----------------------------------------------\n";
}

function VERIFICAÇÃO_DA_VIGA_N_CAM($VIGA_ACO, $B_W, $B_MIN1, $C1_MIN1, $B_MIN2, $C1_MIN2,$B_MIN3, $C1_MIN3, $B_MIN4, $C1_MIN4, $N_BAR)
{
  echo "-----------------------------------------------\n";
  echo "PROCESSAMENTO DA VIGA:\n";
  echo "-----------------------------------------------\n";
  $NUMERADOR_XG = 0;
  $NUMERADOR_YG = 0;
  $DENOMINADOR = 0;
  for ($I = 0; $I < $N_BAR; $I++):
        $NUMERADOR_XG = $NUMERADOR_XG + $VIGA_ACO[$I][5] * $VIGA_ACO[$I][3];
        $NUMERADOR_YG = $NUMERADOR_YG + $VIGA_ACO[$I][5] * $VIGA_ACO[$I][4];
        $DENOMINADOR = $DENOMINADOR + $VIGA_ACO[$I][5];
  endfor;
  $C_1H =  $NUMERADOR_XG / $DENOMINADOR;
  $C_1V =  $NUMERADOR_YG / $DENOMINADOR;
  $VIGA_YCG = [$C_1H, $C_1V];
  $C1 = min($VIGA_YCG);
  echo "-----------------------------------------------\n";
  echo "C1h                                     =$C_1H mm \n";
  echo "C1v                                     =$C_1V mm \n";
  echo "C1m                                     =$C1 mm\n";
  echo "-----------------------------------------------\n";
  [$CRITERIO_BW1, $CRITERIO_BW2, $CRITERIO_BW3, $CRITERIO_BW4, $CRITERIO_C1_1, $CRITERIO_C1_2, $CRITERIO_C1_3, $CRITERIO_C1_4] = COMPARA_TABELA_TRRF($C1, $B_W, $B_MIN1, $C1_MIN1, $B_MIN2, $C1_MIN2,$B_MIN3, $C1_MIN3, $B_MIN4, $C1_MIN4);
  echo "-----------------------------------------------\n";
  echo "TABELA DIMENSÕES MÍNIMAS:\n\n";
  echo "-----------------------------------------------\n";
  echo "                        Atende bmin   Atende c1 \n";
  echo "Combinação 1:               $CRITERIO_BW1         $CRITERIO_C1_1\n";
  echo "Combinação 2:               $CRITERIO_BW2         $CRITERIO_C1_2\n";
  echo "Combinação 3:               $CRITERIO_BW3         $CRITERIO_C1_3\n";
  echo "Combinação 4:               $CRITERIO_BW4         $CRITERIO_C1_4\n";
  echo "-----------------------------------------------\n";
}

function COMPARA_TABELA_TRRF($C1, $B_W, $B_MIN1, $C1_MIN1, $B_MIN2, $C1_MIN2,$B_MIN3, $C1_MIN3, $B_MIN4, $C1_MIN4)
{
if ($B_W > $B_MIN1):
    $CRITERIO_BW1 = 'Sim';
else:
    $CRITERIO_BW1 = 'Não';
endif;
if ($B_W > $B_MIN2):
    $CRITERIO_BW2 = 'Sim';
else:
    $CRITERIO_BW2 = 'Não';
endif;
if ($B_W > $B_MIN3):
    $CRITERIO_BW3 = 'Sim';
else:
    $CRITERIO_BW3 = 'Não';
endif;
if ($B_W > $B_MIN4):
    $CRITERIO_BW4 = 'Sim';
else:
    $CRITERIO_BW4 = 'Não';
endif;
if ($C1 > $C1_MIN1):
    $CRITERIO_C1_1 = 'Sim';
else:
    $CRITERIO_C1_1 = 'Não';
endif;
if ($C1 > $C1_MIN2):
    $CRITERIO_C1_2 = 'Sim';
else:
    $CRITERIO_C1_2 = 'Não';
endif;
if ($C1 > $C1_MIN3):
    $CRITERIO_C1_3 = 'Sim';
else:
    $CRITERIO_C1_3 = 'Não';
endif;
if ($C1 > $C1_MIN4):
    $CRITERIO_C1_4 = 'Sim';
else:
    $CRITERIO_C1_4 = 'Não';
endif;

return array($CRITERIO_BW1, $CRITERIO_BW2, $CRITERIO_BW3, $CRITERIO_BW4, $CRITERIO_C1_1, $CRITERIO_C1_2, $CRITERIO_C1_3, $CRITERIO_C1_4);

}

#   /$$$$$$  /$$$$$$$  /$$$$$$$$ /$$$$$$$$       /$$$$$$$$ /$$$$$$$$  /$$$$$$  /$$   /$$ /$$   /$$  /$$$$$$  /$$        /$$$$$$   /$$$$$$  /$$$$$$ /$$$$$$$$  /$$$$$$ 
#  /$$__  $$| $$__  $$| $$_____/| $$_____/      |__  $$__/| $$_____/ /$$__  $$| $$  | $$| $$$ | $$ /$$__  $$| $$       /$$__  $$ /$$__  $$|_  $$_/| $$_____/ /$$__  $$
# | $$  \__/| $$  \ $$| $$      | $$               | $$   | $$      | $$  \__/| $$  | $$| $$$$| $$| $$  \ $$| $$      | $$  \ $$| $$  \__/  | $$  | $$      | $$  \__/
# | $$ /$$$$| $$$$$$$/| $$$$$   | $$$$$            | $$   | $$$$$   | $$      | $$$$$$$$| $$ $$ $$| $$  | $$| $$      | $$  | $$| $$ /$$$$  | $$  | $$$$$   |  $$$$$$ 
# | $$|_  $$| $$____/ | $$__/   | $$__/            | $$   | $$__/   | $$      | $$__  $$| $$  $$$$| $$  | $$| $$      | $$  | $$| $$|_  $$  | $$  | $$__/    \____  $$
# | $$  \ $$| $$      | $$      | $$               | $$   | $$      | $$    $$| $$  | $$| $$\  $$$| $$  | $$| $$      | $$  | $$| $$  \ $$  | $$  | $$       /$$  \ $$
# |  $$$$$$/| $$      | $$$$$$$$| $$$$$$$$         | $$   | $$$$$$$$|  $$$$$$/| $$  | $$| $$ \  $$|  $$$$$$/| $$$$$$$$|  $$$$$$/|  $$$$$$/ /$$$$$$| $$$$$$$$|  $$$$$$/
#  \______/ |__/      |________/|________/         |__/   |________/ \______/ |__/  |__/|__/  \__/ \______/ |________/ \______/  \______/ |______/|________/ \______/ 

?>  