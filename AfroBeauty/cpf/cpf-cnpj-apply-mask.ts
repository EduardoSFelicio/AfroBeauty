// Máscara de CPF e CNPJ
// Evita que o usuário fique digitando caracteres desnecessários para
// o uso do formulário, facilitando o tratamento de dados posteriormente.

export const cpfCnpjApplyMask = (value: string): string => {
  const digits = value.replace(/\D/g, '');

  if (digits.length <= 11) {
    // Máscara CPF
    return digits
      .replace(/(\d{3})(\d)/, '$1.$2')
      .replace(/(\d{3})(\d)/, '$1.$2')
      .replace(/(\d{3})(\d{1,2})$/, '$1-$2')
      .slice(0, 14);
  } else {
    // Máscara CNPJ
    return digits
      .replace(/^(\d{2})(\d)/, '$1.$2')
      .replace(/^(\d{2})\.(\d{3})(\d)/, '$1.$2.$3')
      .replace(/\.(\d{3})(\d)/, '.$1/$2')
      .replace(/(\d{4})(\d{1,2})$/, '$1-$2')
      .slice(0, 18);
  }
    
  function configurarMascaraCpfCnpj() {
    const input = document.getElementById('cpf') as HTMLInputElement;

    input.addEventListener('input', () => {
      const pos = input.selectionStart || 0;
      const valorOriginal = input.value;
      const valorFormatado = cpfCnpjApplyMask(valorOriginal);

      input.value = valorFormatado;

      // Ajusta o cursor para não pular
      const diff = valorFormatado.length - valorOriginal.length;
      input.setSelectionRange(pos + diff, pos + diff);
    });
  }

  (window as any).configurarMascaraCpfCnpj = configurarMascaraCpfCnpj;

  // Executa ao carregar
  configurarMascaraCpfCnpj();
};
