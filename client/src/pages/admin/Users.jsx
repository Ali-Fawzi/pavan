import Layout from "../../layout/Layout";
import {useMemo} from "react";
import {getUsersData} from "../../data/dummy";
import Table from "../../component/Table";

export default function Users(){
    const columns = useMemo(
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
                accessor: 'role',
            },
        ],
        []
    );

    const data = useMemo(() => getUsersData(), []);

    return (
        <Layout>
            <div className="w-full h-full">
                <h1 className="text-2xl font-bold mb-6">User Table</h1>
                <Table columns={columns} data={data} />
            </div>
        </Layout>

    );
}