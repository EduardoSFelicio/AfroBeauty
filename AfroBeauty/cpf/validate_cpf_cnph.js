import { ValidateCNPJ } from './validate-cnpj.js';
import { ValidateCPF } from './validate-cpf.js';

function ValidateCpfCnpj(value) {
  const cleaned = value.replace(/\D/g, '');

  if (cleaned.length === 11) {
    return ValidateCPF(value);
  } else if (cleaned.length === 14) {
    return ValidateCNPJ(value);
  }

  return false;
}

// Torna acessível no HTML (ex: window.ValidateCpfCnpj)
window.ValidateCpfCnpj = ValidateCpfCnpj;
