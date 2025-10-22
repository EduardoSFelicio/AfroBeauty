// Arquivo centralizado de export de CPF e CNPJ.

import { ValidateCNPJ } from './validate-cnpj';
import { ValidateCPF } from './validate-cpf';

export const ValidateCpfCnpj = (value: string): boolean => {
  const cleanedValue = value.replace(/\D/g, '');

  if (cleanedValue.length === 11) {
    // CPF
    return ValidateCPF(value);
  } else if (cleanedValue.length === 14) {
    // CNPJ
    return ValidateCNPJ(value);
  }

  return false;
};
(window as any).ValidateCpfCnpj = ValidateCpfCnpj;