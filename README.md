# Lá na Rô Convites — réplica local

O conteúdo em `site/` foi recuperado do ZIP exportado do `public_html` do cPanel. O `index.html` é o mesmo arquivo servido pela página inicial pública observada. O diretório `site/` deve ser usado como raiz web no ambiente de prévia ou hospedagem.

## Estado da cópia

- As páginas HTML, estilos, scripts e imagens foram mantidos com os caminhos originais para preservar o desenho e o comportamento responsivo existentes.
- O ZIP também continha uma loja OpenCart e páginas antigas de galeria alimentadas por banco de dados.
- Configurações de conexão, logs, arquivos SQL e uma cópia antiga de backup foram excluídos da árvore de trabalho por segurança. O arquivo ZIP original continua no computador da responsável.
- O remetente PHP antigo foi substituído por `site/smtp/ztmail.php`, que não contém senha. Para ativar o formulário legado, configure `LANARO_CONTACT_EMAIL` no ambiente PHP do novo host; `LANARO_MAIL_FROM` é opcional. O formulário não foi submetido durante a cópia.

## Pendências para migrar

Esta árvore preserva o frontend que veio no ZIP, mas ainda não é uma migração operacional completa. As páginas antigas de galeria e a loja dependem dos bancos/configurações que não vieram de forma segura no pacote. Antes de escolher o novo host, recuperar somente os dados públicos necessários ao catálogo/galeria, preparar configurações novas fora do Git e testar os fluxos PHP em um endereço de prévia.

O `site/index.php` legado redireciona para uma URL HTTP absoluta. A hospedagem de destino precisa ser configurada e testada para servir `/index.html` corretamente em `/` antes de qualquer troca de DNS.

Nenhuma alteração foi feita no servidor cPanel nem no DNS do domínio.
