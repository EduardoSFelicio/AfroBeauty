function ValidateCNPJ(cnpj) {
  cnpj = cnpj.replace(/[^\d]+/g, '');

  if (cnpj.length !== 14) return false;
  if (/^(\d)\1+$/.test(cnpj)) return false;

  let soma = 0;
  let pesos = [5, 4, 3, 2, 9, 8, 7, 6, 5, 4, 3, 2];

  for (let i = 0; i < 12; i++) {
    soma += parseInt(cnpj.charAt(i)) * pesos[i];
  }

  let resto = soma % 11;
  let digito1 = resto < 2 ? 0 : 11 - resto;

  if (parseInt(cnpj.charAt(12)) !== digito1) return false;

  soma = 0;
  pesos = [6, 5, 4, 3, 2, 9, 8, 7, 6, 5, 4, 3, 2];

  for (let i = 0; i < 13; i++) {
    soma += parseInt(cnpj.charAt(i)) * pesos[i];
  }

  resto = soma % 11;
  let digito2 = resto < 2 ? 0 : 11 - resto;

  return parseInt(cnpj.charAt(13)) === digito2;
}
