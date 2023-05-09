<?php
class Model
{
    protected $conexao;
    protected $defaultOrder = 'id DESC';
    protected $searchColumns = '';

    protected function buildFilter ($params)
    {
        $order = !empty($params['order']) ? str_replace(':', ' ', $params['order']) : $this->defaultOrder;
        $init = !empty($params['init']) ? $params['init'] : 0;
        $limit = (!empty($params['limit']) && $params['limit'] > 0) ? $params['limit'] : 50;

        return "ORDER BY {$order} LIMIT {$init}, {$limit}";
    }

    protected function buildSearch ($search)
    {
        if (!empty($search) && !empty($this->searchColumns)) {
            $searchColumns = explode(';', $this->searchColumns);

            foreach ($searchColumns as $index => $column) {
                $searchColumns[$index] = "$column LIKE '%$search%'";
            }

            $searchColumns = implode(' || ', $searchColumns);

            return "AND ($searchColumns)";
        }

        return '';
    }

    protected function buildMultipleInsert ($data)
    {
        // Cria um array para armazenar a string sql de cada linha a ser inserida
        $fields = [];
        // Cria um array para armazenar os valores de cada parâmetro a ser substituído no SQL final
        $params = [];
        // Para cada linha a ser inserida
        foreach ($data as $rowIndex => $rowData)
        {
            // Cria um array da linha para armazenar o nome dos parâmetros que representam cada coluna
            $rowSql = [];
            // Para cada coluna dentro da linha
            foreach ($rowData as $colIndex => $value)
            {
                // Armazena o parâmetro da coluna seguindo o padrão ':linha_coluna'. Ex: ':1_nome'
                $column = ":{$rowIndex}_{$colIndex}";
                // Alimenta o array da linha
                $rowSql[] = $column;
                // Armazena o valor do parâmetro a ser substituído na execução da query
                $params[$column] = $value;
            }
            // Adiciona ao array que criará a string sql os parâmetros para a linha. Ex da linha 1: '(:1_nome, :1_cidade, :1_telefone)'
            $fields[] = '(' . implode(',', $rowSql) . ')';
        }

        $fields = implode(',', $fields);

        return ['campos' => $fields, 'params' => $params];
    }

    protected function buildInsertUpdate ($data)
    {
        $fields = [];
        $params = [];
        foreach($data as $field => $value){
            //Insere os dados no array que será utilizado no execute ([campo] = valor)
            $params[$field] = $value;

            //Insere dados no array que será utilizado na string com a query
            $fields[] = "$field = :$field";
        }

        //Transforma o array em string
        $fields = implode(',', $fields);

        return ['campos' => $fields, 'params' => $params];
    }

    public function __construct(Conexao $conexao)
    {
        $this->conexao = $conexao;
    }
}