// Função que aplica a máscara de CPF ou CNPJ
function aplicarMascaraCpfCnpj(valor) {
  // Remove tudo que não for dígito
  var numeros = valor.replace(/\D/g, '');

  if (numeros.length <= 11) {
    // Formata como CPF: 000.000.000-00
    return numeros
      .replace(/(\d{3})(\d)/, '$1.$2')
      .replace(/(\d{3})(\d)/, '$1.$2')
      .replace(/(\d{3})(\d{1,2})$/, '$1-$2');
  } else {
    // Formata como CNPJ: 00.000.000/0000-00
    return numeros
      .replace(/^(\d{2})(\d)/, '$1.$2')
      .replace(/^(\d{2})\.(\d{3})(\d)/, '$1.$2.$3')
      .replace(/\.(\d{3})(\d)/, '.$1/$2')
      .replace(/(\d{4})(\d)/, '$1-$2');
  }
}

// Configura o input para usar a máscara automaticamente durante a digitação
function configurarMascaraCpfCnpj() {
  var input = document.getElementById('cpf');

  input.addEventListener('input', function() {
    // Posição atual do cursor
    var pos = input.selectionStart || 0;
    // Valor atual digitado
    var valorOriginal = input.value;
    // Valor com formatação
    var valorFormatado = aplicarMascaraCpfCnpj(valorOriginal);

    input.value = valorFormatado;

    // Ajusta a posição do cursor para evitar pulos bruscos
    var diff = valorFormatado.length - valorOriginal.length;
    input.setSelectionRange(pos + diff, pos + diff);
  });
}

// Inicializa a máscara quando a página carregar
configurarMascaraCpfCnpj();