<?php

class Record
{
    private $db;

    public function __construct($dbConnection)
    {
        $this->db = $dbConnection;
    }

    /**
     * Obtener registros con filtros.
     *
     * @param array $filters
     * @return array
     */
    public function getFilteredRecords($filters)
    {
        $query = "SELECT r.id, u.nombre AS usuario, r.fecha, r.tipo, r.monto, r.estado
                  FROM registros r
                  INNER JOIN usuarios u ON r.usuario_id = u.id
                  WHERE 1=1";

        $params = [];

        // Aplicar filtros dinámicos
        if (!empty($filters['user'])) {
            $query .= " AND u.nombre LIKE :user";
            $params[':user'] = '%' . $filters['user'] . '%';
        }
        if (!empty($filters['start_date'])) {
            $query .= " AND r.fecha >= :start_date";
            $params[':start_date'] = $filters['start_date'];
        }
        if (!empty($filters['end_date'])) {
            $query .= " AND r.fecha <= :end_date";
            $params[':end_date'] = $filters['end_date'];
        }
        if (!empty($filters['type'])) {
            $query .= " AND r.tipo = :type";
            $params[':type'] = $filters['type'];
        }
        if (isset($filters['status']) && $filters['status'] !== '') {
            $query .= " AND r.estado = :status";
            $params[':status'] = $filters['status'] === 'true' ? 1 : 0;
        }

        $stmt = $this->db->prepare($query);
        $stmt->execute($params);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
