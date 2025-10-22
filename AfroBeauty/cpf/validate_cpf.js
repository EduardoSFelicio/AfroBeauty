function ValidateCPF(cpf) {
  const cpfLimpo = cpf.replace(/\D/g, '');

  if (cpfLimpo.length !== 11) return false;
  if (/^(\d)\1{10}$/.test(cpfLimpo)) return false;

  let soma = 0;
  let peso = 10;

  for (let i = 0; i < 9; i++) {
    soma += parseInt(cpfLimpo.charAt(i)) * peso--;
  }

  let primeiroDigito = 11 - (soma % 11);
  if (primeiroDigito >= 10) primeiroDigito = 0;

  if (parseInt(cpfLimpo.charAt(9)) !== primeiroDigito) return false;

  soma = 0;
  peso = 11;

  for (let i = 0; i < 10; i++) {
    soma += parseInt(cpfLimpo.charAt(i)) * peso--;
  }

  let segundoDigito = 11 - (soma % 11);
  if (segundoDigito >= 10) segundoDigito = 0;

  return parseInt(cpfLimpo.charAt(10)) === segundoDigito;
}