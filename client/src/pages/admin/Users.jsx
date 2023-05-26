import React, {useEffect, useState} from 'react';
import Layout from "../../layout/Layout";
import api from "../../api/api";
import {useTable} from "react-table";

export default function Users() {
    const [users, setUsers] = useState([]);
    useEffect(() => {
        api
            .get('/users')
            .then((res) => {
                setUsers(res.data);
            })
            .catch((error) => {
                console.error('Error fetching users:', error);
            });
    }, []);

    const data = React.useMemo(() => users, [users]);

    const columns = React.useMemo(
        () => [
            {
                Header: 'ID',
                accessor: 'id',
            },
            {
                Header: 'Name',
                accessor: 'name',
            },
            {
                Header: 'Email',
                accessor: 'email',
            },
            {
                Header: 'Role',
                accessor: 'role.name',
            },
        ],
        []
    );

    const { getTableProps, getTableBodyProps, headerGroups, rows, prepareRow } = useTable({ columns, data });

    return (
        <Layout>
            <table {...getTableProps()} style={{ width: '100%', textAlign: 'left' }}>
                <thead>
                {headerGroups.map((headerGroup) => (
                    <tr {...headerGroup.getHeaderGroupProps()}>
                        {headerGroup.headers.map((column) => (
                            <th {...column.getHeaderProps()}>{column.render('Header')}</th>
                        ))}
                    </tr>
                ))}
                </thead>
                <tbody {...getTableBodyProps()}>
                {rows.map((row) => {
                    prepareRow(row);
                    return (
                        <tr {...row.getRowProps()}>
                            {row.cells.map((cell) => {
                                return <td {...cell.getCellProps()}>{cell.render('Cell')}</td>;
                            })}
                        </tr>
                    );
                })}
                </tbody>
            </table>
        </Layout>
    );
}
