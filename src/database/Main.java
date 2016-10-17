package database;
import java.sql.*;


public class Main {
	public static void main(String args[])
	{
		try {
			
			DriverManager.registerDriver(new oracle.jdbc.driver.OracleDriver());
			//open sql connection through SSH tunnel
			Connection con = DriverManager.getConnection("jdbc:oracle:thin:@localhost:1522:ug", "ora_w5y9a", "a20030145");
			Statement smt = con.createStatement();
			
			//get everything from table AUTHORS
			ResultSet rs = smt.executeQuery("select * from Authors");
			
			while(rs.next())
			{
				System.out.println(rs.getString(1));
			}
			
		
		} catch (SQLException e) {
			// TODO Auto-generated catch block
			e.printStackTrace();
		}
		
		
	}

}

