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
                {/*<Table columns={columns} data={data} />*/}
                <table className="table-auto w-full">
                    <thead>
                    <tr>
                        <th>Song</th>
                        <th>Artist</th>
                        <th>Year</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr>
                        <td>The Sliding Mr. Bones (Next Stop, Pottersville)</td>
                        <td>Malcolm Lockyer</td>
                        <td>1961</td>
                    </tr>
                    <tr>
                        <td>Witchy Woman</td>
                        <td>The Eagles</td>
                        <td>1972</td>
                    </tr>
                    <tr>
                        <td>Shining Star</td>
                        <td>Earth, Wind, and Fire</td>
                        <td>1975</td>
                    </tr>
                    </tbody>
                </table>
            </div>
        </Layout>

    );
}