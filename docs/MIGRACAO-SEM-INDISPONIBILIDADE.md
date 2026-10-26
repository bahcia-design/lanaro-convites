# Migração do Lá na Rô sem interromper o site

## Situação atual

- A pasta `site/` preserva os arquivos do `public_html` recebidos no ZIP. As páginas HTML, estilos, scripts e imagens do site público foram mantidos.
- O pacote também contém `site/loja/` (OpenCart) e páginas em PHP que dependem de banco de dados, incluindo a galeria. Os bancos e as configurações de produção não estão neste repositório.
- `site/index.php` contém um redirecionamento HTTP legado. O destino precisa servir a página inicial HTTPS corretamente antes da mudança de DNS.
- `site/smtp/ztmail.php` foi sanitizado: o envio do formulário requer configuração de e-mail no ambiente do novo servidor.
- Nada neste roteiro altera o DNS ou o cPanel atual.

## Antes de escolher ou configurar a hospedagem nova

1. Confirmar com a responsável quais partes estão em uso: site institucional, `/loja`, `/gallery`, formulários e área administrativa.
2. Na hospedagem atual, identificar em **cPanel → Bancos de dados MySQL** os bancos e usuários usados pela loja e pela galeria. Anotar apenas os nomes; não enviar senhas por GitHub ou chat.
3. Exportar cada banco pelo **phpMyAdmin → selecionar banco → Exportar → Rápido → SQL**. Guardar os arquivos em local privado, fora de `public_html` e fora deste repositório.
4. Em **Gerenciador de arquivos**, gerar um backup integral de `public_html` em uma pasta de backup fora de `public_html`. Não tentar criar `assets.zip` dentro da própria pasta `assets`: isso causa erro de origem e destino iguais ou inclui apenas a pasta errada. O ZIP de `public_html` já recebido é uma cópia de arquivos, mas não substitui os exports SQL.
5. Se não souber qual banco pertence a cada parte, localizar os arquivos de configuração correspondentes no cPanel e comparar o nome do banco com a lista do passo 2. Não publicar esses arquivos com credenciais.

## Preparação do novo servidor

O destino precisa executar PHP e MySQL/MariaDB, além de oferecer extensões e versão do PHP compatíveis com a versão antiga do OpenCart e com os scripts legados. Hospedagem apenas estática não atende a loja, galeria e formulários PHP.

1. Criar o ambiente novo com um endereço de prévia ou subdomínio temporário; manter o domínio principal apontado para o cPanel.
2. Restaurar os bancos no servidor novo e guardar configurações e credenciais fora do Git, com permissões restritas.
3. Enviar os arquivos da branch `codex/lanaro-replica`, usando `site/` como raiz pública, e aplicar apenas as configurações novas do ambiente.
4. Ajustar no ambiente novo as configurações do OpenCart (catálogo e administração), galeria, URLs HTTPS e envio de e-mail. Configurar `LANARO_CONTACT_EMAIL` no PHP para o formulário sanitizado.
5. Conferir permissões de escrita exigidas pelo OpenCart para armazenamento, cache, sessão e imagens. Não tornar toda a árvore pública gravável.

## Validação no endereço de prévia

Antes de qualquer troca, conferir manualmente:

- `/` e `/index.html`, navegação, imagens, fontes, CSS e layout em celular e computador.
- Todas as páginas principais e links internos, inclusive páginas antigas em `.php`.
- `/loja`: catálogo, busca, carrinho, finalização, e-mails e acesso administrativo. Se pagamentos ou pedidos não estiverem ativos, não habilitá-los no teste.
- `/gallery`: listagem e imagens públicas; testar a administração apenas com a responsável.
- Formulários de contato e mensagens recebidas; confirmar envio sem expor credenciais.
- HTTPS, redirecionamentos, páginas 404, arquivos de mídia e logs sem erros PHP.

Não enviar formulário de produção para fazer teste. Usar o endereço de prévia e destinatário controlado pela responsável.

## Corte de DNS

1. Com antecedência, reduzir o TTL dos registros DNS do domínio, se o provedor permitir, e aguardar o TTL anterior expirar.
2. Confirmar o backup final de arquivos e bancos e o plano de retorno. Se a loja ou outra área aceitar gravações, definir como sincronizar as gravações ocorridas durante a propagação. Se não houver banco compartilhado ou replicação entre servidores, pausar temporariamente novos pedidos durante a sincronização final; sem isso, usuários em servidores diferentes podem gravar dados divergentes.
3. Fazer a sincronização final e alterar somente os registros web necessários (A/AAAA ou CNAME, conforme o novo provedor). Preservar registros de e-mail (MX, SPF, DKIM e DMARC) quando o e-mail continuar no provedor atual.
4. Manter o cPanel e o banco antigos ativos. Acompanhar acesso ao site, formulários, pedidos e logs enquanto o DNS propaga.
5. Se houver falha, restaurar os registros web para o cPanel. Antes do retorno, reconciliar os dados gravados no servidor novo para não perder pedidos ou mensagens.
6. Só encerrar a hospedagem antiga depois de confirmar que o novo ambiente está estável e que todos os dados e e-mails foram preservados.

## Critério para autorizar a mudança

Não trocar o DNS enquanto os bancos da loja/galeria estiverem faltando, as rotas PHP não estiverem testadas, o formulário não estiver configurado ou não existir um plano para dados gravados durante a propagação. O visual do site por si só não confirma que a migração operacional está completa.
